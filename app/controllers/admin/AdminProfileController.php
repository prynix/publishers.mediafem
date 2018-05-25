<?php

class AdminProfileController extends BaseController {
    /*
     * Muestra la pantalla de mi cuenta
     */

    public function getIndex() {
        $profile = Profile::findByUserID(Session::get('user.id'));

        $infoBank = BankDetail::findByPublisherID(Session::get('publisher.id'));

        $infoPaypal = PaypalDetail::findByPublisherID(Session::get('publisher.id'));

        //$user = Sentry::getUser();
        $user = Sentry::findUserByLogin(Session::get('user.email'));
        
        $messages = Messages::getMessagesByUser(Session::get('user.administrator_id'));

        $publisher = Publisher::find(Session::get('publisher.id'));
        $tax = $publisher->getTax();
        // Genero un nuevo codigo de reseteo de contrasena
        $resetCode = $user->getResetPasswordCode();

        return View::make('profile.index', ['infoAccount' => $profile, 'resetCode' => $resetCode, 'infoPaypal' => $infoPaypal, 'infoBank' => $infoBank, 'messages' => $messages, 'tax' => $tax]);
    }
    
    public function getPaymentPreferences($admin_id) {
        $infoBank = BankDetail::findByAdministratorID($admin_id);
        $infoPaypal = PaypalDetail::findByAdministratorID($admin_id);
        return View::make('admin.users.accountPayment', ['paypal' => $infoPaypal, 'bank' => $infoBank,]);
    }

    /*
     * Muestro el cartel de notificaciones
     */

    public function getNotifications() {
        $messages = Messages::getNotificationsByUser(Session::get('user.administrator_id'));

        return View::make('profile.userNotifications', ['messages' => $messages]);
    }

    public function getTaxUsa() {
        return Response::download(public_path() . '/tax_data/fw9.pdf');
    }

    public function getTaxOther() {
        return Response::download(public_path() . '/tax_data/fw8ben.pdf');
    }

    /*
     * Traigo la informacion de un mensaje seleccionado por el usuario
     */

    public function getMessage($id) {
        $message = Messages::find($id);

        return View::make('profile.messageView', ['message' => $message]);
    }

    /*
     * Seteo el nuevo idioma seleccionado por el usuario
     */

    public function setLang($lang) {

        $profile = Profile::findByUserID(Session::get('user.id'));

        $profile->setLanguage($lang);

        $profile->forceSave();
        //if (!$profile->forceSave())
        //return FALSE;

        Session::put('user.language', $lang);
        return Redirect::back();
    }

    /*
     * Actualiza los datos bancarios del usuario
     */

    public function setBank() {
        try {
            $bank = BankDetail::findByAdministratorID(Session::get('admin.id'));

            if (!$bank)
                $bank = new BankDetail();

            $bank->fill(Input::all());

            $bank->setCountry(Input::get('bnk_country_id'));
            $bank->setAdministrator(Input::get('bnk_administrator_id'));

            Session::forget('error');

            if (!$bank->save()) {
                Session::put('error', $bank->errors());

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $bank->errors()]);
                return Redirect::back();
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
     * Actualiza la cuenta de PayPal del usuario
     */

    public function setPaypal() {
        try {
            $paypal = PaypalDetail::findByAdministratorID(Input::get('ppl_administrator_id'));

            if (!$paypal)
                $paypal = new PaypalDetail();

            $paypal->fill(Input::all());
            
            $paypal->setAdministrator(Input::get('ppl_administrator_id'));

            Session::forget('error');

            if (!$paypal->save()) {
                Session::put('error', $paypal->errors());

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $paypal->errors()]);
                return Redirect::back();
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
     * Actualiza las contrasenas del usuario en la base de datos
     */

    public function setNewPassword() {
        try {
            // obtengo los datos ingresados en el formulario
            $data = array(
                'password' => Input::get('password'),
                'repeat_password' => Input::get('repeat_password'),
                'code' => Input::get('code'),
                'user_id' => Input::get('user_id')
            );

            // intento resetear la contrasena
            $resetPassword = Pluton::resetPassword($data['user_id'], $data['code'], $data['password'], $data['repeat_password']);

            Session::forget('error');

            if (isset($resetPassword['error'])) {
                Session::put('error', $resetPassword['message']);

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $resetPassword['message']]);

                return Redirect::back();
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
     * Actualiza el correo electronico del usuario en la base de datos
     */

    public function setEmail() {
        try {

            $change = Pluton::changeEmail(Session::get('user.id'), Input::get('email'));

            Session::forget('error');

            if (isset($change['error'])) {
                Session::put('error', $change['message']);

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $change['message']]);

                return Redirect::back();
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
     * Actualiza el archivo de fiscalia
     */

    public function setTax() {
        $file = Input::file('file');
        if ($file->guessClientExtension() == 'pdf') {
            $extension = '.' . $file->guessClientExtension();
            $publisher = Publisher::find(Session::get('publisher.id'));
            $fileName = $publisher->getName() . '_' . Session::get('user.id');
            $fileName = str_replace(" ","", $fileName);
            $path = public_path() . '/tax_data/';
            $file->move($path, $fileName . $extension);
            $publisher->pbl_tax_complete = 1;
            $publisher->pbl_tax_file = $fileName . $extension;
            $publisher->forceSave();
            Session::put('tax.send', 1);
            Session::put('tax.message', 'The form was sending. Thanks!');
            return Redirect::to(URL::previous() . '#accountTax');
        } elseif(strpos($file->getMimeType(),'image') !== false) {
            $extension = '.' . $file->guessClientExtension();
            $publisher = Publisher::find(Session::get('publisher.id'));
            $fileName = $publisher->getName() . '_' . Session::get('user.id');
            $fileName = str_replace(" ","", $fileName);
            $path = public_path() . '/tax_data/';
            $file->move($path, $fileName . $extension);
            $publisher->pbl_tax_complete = 1;
            $publisher->pbl_tax_file = $fileName . $extension;
            $publisher->forceSave();
            Session::put('tax.send', 1);
            Session::put('tax.message', 'The form was sending. Thanks!');
            return Redirect::to(URL::previous() . '#accountTax');
        } else {
            Session::put('tax.send', 3);
            Session::put('tax.message', 'Incorrect file format!');
            return Redirect::to(URL::previous() . '#accountTax');
        }

        /* Zipper::make($pathZipFile . $fileName . '.zip')->add($pathTmpFile, $fileName . $extension);
          var_dump($pathZipFile . $fileName . $extension);

          if (File::exists($pathTmpFile . $fileName . $extension)) {
          rmdir($pathTmpFile);
          //File::delete($pathTmpFile . $fileName . $extension);
          } */
    }

    /*
     * Guarda en la base de datos del formulario "Informacion de la cuenta"
     */

    public function setAccountInfo() {
        try {
            // buscamos el perfil del usuario
            $user = User::find(Session::get('user.id'));
            $profile = $user->profile;
            $publisher = $user->publisher;

            Session::forget('error');

            // si no existe se crea el registro en la base de datos
            if (!$profile)
                $profile = new Profile();

            // se rellenan los campos
            $profile->fill(Input::all());
            $profile->setUser($user->getId());
            $profile->setCountry(Input::get('prf_country'));
            $profile->setLanguage(Session::get('user.language'));

            if (!$profile->validate()) {
                Session::put('error', $profile->errors());

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $profile->errors()]);
                return Redirect::back();
            }

            if (!$publisher) {
                $publisher = new Publisher();
                $publisher->fill(Input::all());
                $publisher->setAdServer(Session::get('adserver.id'));
                $publisher->setUser($user->getId());
                if (!$publisher->validate()) {
                    Session::put('error', $publisher->errors());

                    if (Request::ajax())
                        return Response::json(['error' => 2, 'messages' => $publisher->errors()]);
                    return Redirect::back();
                }
                $publisher->save();
                $mediaBuyer = $user->getAdserver()->pivot->media_buyer_id;
                //Asignar Media Buyer
                if($mediaBuyer){
                    $publisher->setMediaBuyer($mediaBuyer);
                    $publisher->forceSave();
                    Api::assignMediaBuyer($publisher->getFirstAdserverId(), $publisher);
                }
                $user->adservers()->detach(Session::get('adserver.id'));
                //self::sendEmailToMedia($publisher, $profile);
                Session::put('publisher.id', $publisher->getId());
            }
            $profile->save();
            // Se crea el publisher en la DB y en el adserver
            //self::createPublisher();

            Session::put('user.completeData', TRUE);

            if (Request::ajax())
                return Response::json(['error' => 0]);

            return Redirect::back();
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

    public function sendEmailToMedia($publisher, $profile) {
        $adserver = Adserver::find(Session::get('adserver.id'));
        $adserverName = $adserver->getName();
        $adserverKey = $publisher->getAdserverKey(Session::get('adserver.id'));
        $data = array(
            'publisher' => $publisher,
            'profile' => $profile,
            'adserverName' => $adserverName,
            'adserverKey' => $adserverKey,
            'email' => Session::get('user.email')
        );

        // se envía el correo a medios informando el registro del nuevo publisher
        Mailer::send('emails.alert.newPublisher', $data, 'media@mediafem.com', '', 'Se ha creado un nuevo publisher - santiago.lennard@adtomatik.com');
    }

    /*
     * Crea el publisher en el adserver y en la base de datos
     */

    /* public function createPublisher($url) {
      $publisher = new Publisher;
      $publisher->setName($url);
      $publisher->setUser(Session::get('user.id'));
      $publisher->save();
      return $publisher->getId();
      } */
}
