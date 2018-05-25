<?php

class AdminMessagesController extends BaseController {
    /*
     * Muestra la pantalla de mensajes
     */

    public function getIndex() {
        $new_group = MessagesDefault::lastGroup() + 1;

        $messages_default = MessagesGroupsDefault::all();

        $publishers = Publisher::all();

        $messages = Messages::all();

        return View::make('admin.messages.index', ['new_group' => $new_group, 'messages_default' => $messages_default, 'publishers' => $publishers, 'messages' => $messages]);
    }

    /*
     * GETs
     */

    public function getDefault() {
        $message = MessagesDefault::find(Input::get('msgd_id'));

        if ($message)
            return Response::json(['subject' => $message->msgd_subject, 'from' => $message->msgd_from, 'content' => $message->msgd_content]);

        return Response::json(['subject' => '', 'from' => '', 'content' => '']);
    }

    /*
     * SETs
     */

    /*
     * Agrega un nuevo mensaje predeterminado en la base de datos
     */

    public function addDefault() {
        try {
            $default = new MessagesDefault();

            $default->fill(Input::all());

            Session::forget('error');

            if (!$default->save()) {
                Session::put('error', $default->errors());

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $default->errors()]);
                return Redirect::back();
            }

            $group = new MessagesGroupsDefault();

            if (Input::get('msgd_group_name')) {
                $group->msgdg_id = Input::get('msgd_group');
                $group->msgdg_name = Input::get('msgd_group_name');

                if (!$group->save()) {
                    Session::put('error', $group->errors());

                    if (Request::ajax())
                        return Response::json(['error' => 1, 'messages' => $group->errors()]);
                    return Redirect::back();
                }
            }

            if (Request::ajax())
                return Response::json(['error' => 0]);

            return Redirect::back();
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

    /*
     * envia un mensaje al usuario y, en caso de ser necesario, envia un correo electronico 
     */

    public function sendMessage() {
        set_time_limit(0);
        ini_set('post_max_size', '99999M');
        ini_set('upload_max_filesize', '999999M');
        ini_set('memory_limit', '999999M');
        ini_set('max_execution_time', '99999');
        ini_set('max_input_time', '99999');
        try {
            $users_id = Input::get('msg_user_id');

            // si seleccione el envio a todos los usuarios
            if (in_array(0, $users_id)) {
                $users = Publisher::all();

                unset($users_id);

                foreach ($users as $user)
                    $users_id[] = $user->pbl_user_id;
            }

            //si selecciono un mensaje predeterminado lo envío segun el idioma del usuario
            if (Input::get('msgdg_id') == 0) {
                // guardo los mensajes por cada usuario
                foreach ($users_id as $user) {
                    if($user < 100)
                        continue;
                    var_dump($user);
                    $message = new Messages();

                    $message->msg_user_id = $user;
                    $message->msg_subject = Input::get('msg_subject');
                    $message->msg_from = Input::get('msg_from');
                    $message->msg_content = Input::get('msg_content');

                    if (!$message->save()) {
                        Session::put('error', $message->errors());

                        if (Request::ajax())
                            return Response::json(['error' => 1, 'messages' => $message->errors()]);
                        return Redirect::back();
                    }

                    // si tiene la opcion de enviar un correo electronico activada
                    if (Input::get('msg_send_email') == 1) {
                        $user = User::find($user);

                        $data = array('content' => Input::get('msg_content'));

                        Mailer::send('emails.blank', $data, $user->email, '', Input::get('msg_subject'));
                    }
                }
            } else {
                // selecciono el mensaje predeterminado segun el idioma del usuario
                foreach ($users_id as $user) {
                    $user = User::find($user);
                    $messageDefault = MessagesDefault::find(Input::get('msgdg_id'));
                    
                    $message = new Messages();

                    $message->msg_user_id = $user->id;
                    $message->msg_subject = $messageDefault->msgd_subject;
                    $message->msg_from = $messageDefault->msgd_from;
                    $message->msg_content = $messageDefault->msgd_content;

                    if (!$message->save()) {
                        Session::put('error', $message->errors());

                        if (Request::ajax())
                            return Response::json(['error' => 1, 'messages' => $message->errors()]);
                        return Redirect::back();
                    }

                    // si tiene la opcion de enviar un correo electronico activada
                    if (Input::get('msg_send_email') == 1) {
                        $data = array('content' => $messageDefault->msgd_content);

                        Mailer::send('emails.blank', $data, $user->email, '', Input::get('msg_subject'));
                    }
                }
            }

            if (Request::ajax())
                return Response::json(['error' => 0]);

            return Redirect::back();
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

}
