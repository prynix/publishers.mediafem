<?php

class AdminUsersController extends BaseController {
    /*
     *
     * GETs
     *  
     */

    /*
     * Muestra la pantalla de mis pagos
     */

    public function getIndex() {
        return View::make('admin.users.index');
    }

    public function addUser() {
        $response = Pluton::addUser(['user_email' => Input::get('email'), 'name' => Input::get('email')]);
        if ($response['errors'] == 0) {
            $user = User::find($response['message']);
            $group = Group::find(Input::get('group_id'));
            $admin = new Administrator();
            $admin->user()->associate($user);
            $admin->group()->associate($group);
            $admin->save();
            if (Input::get('adserver')) {
                foreach (Input::get('adserver') as $ads) {
                    $adserver = Adserver::find($ads);
                    $key = Api::createMediaBuyer($ads, $admin);
                    $admin->adservers()->save($adserver, array('adm_adv_adserver_key' => $key));
                }
            }
        }
        return Response::json(['error' => $response['errors'], 'messages' => $response['message']]);
    }

    public function getUserView($id) {
        $user = User::find($id);
        return View::make('admin.users.show', ['user' => $user]);
    }

    public function loadUsersTable() {
        $allUsers = User::all();
        $users = array();
        foreach ($allUsers as $user) {
            if (!($user->publisher)) {
                $users[] = $user;
            }
        }
        return View::make('admin.tables.tbl_users', ['users' => $users]);
    }

    /*
     *
     * SETs
     *  
     */

    public function createAdministrator() {
        try {
            if (Input::get('prf_name') == "") {
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => 'Debe ingresar nombre al ejecutivo']);
                return Redirect::back();
            }
            $user = User::find((Input::get('user_id')));
            $profile = $user->profile;
            if (!$profile)
                $profile = new Profile();
            $profile->setName(Input::get('prf_name'));
            if (!$user->profile)
                $profile->user()->associate($user);
            $profile->setLanguage('es');

            if (!$profile->forceSave()) {
                Session::put('error', $profile->errors());

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $profile->errors()]);
                return Redirect::back();
            }

            $admin = new Administrator();
            $admin->user()->associate($user);
            $admin->save();

            return Response::json(['error' => 0]);
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

    public function activateUser() {
        try {

            $user = User::find((Input::get('user_id')));
            if (!(Input::get('prf_name') == "")) {
                $profile = $user->profile;
                if (!$profile)
                    $profile = new Profile();
                $profile->setName(Input::get('prf_name'));
                if (!$user->profile)
                    $profile->user()->associate($user);
                $profile->setLanguage('en');

                if (!$profile->forceSave()) {
                    Session::put('error', $profile->errors());

                    if (Request::ajax())
                        return Response::json(['error' => 1, 'messages' => $profile->errors()]);
                    return Redirect::back();
                }
            }

            // busco el usuario segun su ID
            $pluton = Sentry::findUserById(Input::get('user_id'));
            // tratando de activar el usuario
            if (!$pluton->attemptActivation($user->getActivationCode())) {
                Session::put('error', $profile->errors());

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => 'El usuario no pudo ser activado.']);
                return Redirect::back();
            }
            return Response::json(['error' => 0]);
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

    public function updateAdministrator() {
        try {

            $user = User::find((Input::get('user_id')));
            if (!(Input::get('prf_name') == "")) {
                $profile = $user->profile;
                if (!$profile) {
                    $profile = new Profile();
                    $profile->setName(Input::get('prf_name'));
                    $profile->user()->associate($user);
                    $profile->setLanguage('en');
                } else {
                    $profile->setName(Input::get('prf_name'));
                }

                if (!$profile->forceSave()) {
                    Session::put('error', $profile->errors());

                    if (Request::ajax())
                        return Response::json(['error' => 1, 'messages' => $profile->errors()]);
                    return Redirect::back();
                }
            }
            if ($user->administrator) {
                $user->administrator->setRevenueShare(Input::get('adm_revshare'));
                $user->administrator->save();
            }
            return Response::json(['error' => 0]);
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

}
