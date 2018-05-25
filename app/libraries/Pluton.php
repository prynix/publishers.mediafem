<?php

class Pluton {
    /*
     * Obtiene el dominio donde se esta ejecutando el script
     * 
     * @param string compara si el dominio actual corresponde al parametro ingresado
     */

    public static function server($domain = 'localhost') {
        if (Request::server('SERVER_NAME') === $domain)
            return TRUE;

        return FALSE;
    }

    /*
     * Ingreso del usuario al sistema
     *
     * @param array Datos ingresados en el formulario de ingreso
     */

    public static function login($data) {
        try {
            // valida los datos ingresados con la base de datos
            $user = Sentry::authenticate(array(
                        'email' => $data['user_email'],
                        'password' => $data['user_password'],
                            ), $data['user_remember'] == 1 ? true : false );

            if ($user) {
                $user = User::find($user->id);

                Session::put('user.id', $user->id);
                $platform = Platform::find($user->platform_id);
                Session::put('platform.name', $platform->name());
                Session::put('platform.brand', $platform->brand());
                Session::put('platform.logo', $platform->logo());
                Session::put('platform.color1', $platform->color1());
                Session::put('platform.favicon', $platform->favicon());
                Session::put('platform.short', $platform->short());

                if ($user->administrator) {
                    Session::put('user.administrator_id', $user->administrator->adm_publisher_tester);
                    Session::put('admin.id', $user->administrator->adm_id);
                    Session::put('permissions', $user->administrator->group->getEncryptedPermissions());
                    if (Utility::hasPermission('earnings')) {
                        Session::put('earnings', number_format($user->administrator->getActualBalance(), 2, ',', '.'));
                    }
                    if ($user->administrator->adm_publisher_tester) {
                        $publisher = Publisher::find($user->administrator->adm_publisher_tester);
                        if ($publisher) {
                            if ($publisher->imonomy) {
                                Session::put('imonomy', $publisher->imonomy);
                            }
                            Session::put('publisher.show_alert', $publisher->getShowAlert());
                            $platform = Platform::find($publisher->user->platform_id);
                            Session::put('platform.name', $platform->name());
                            Session::put('platform.brand', $platform->brand());
                            Session::put('platform.logo', $platform->logo());
                            Session::put('platform.color1', $platform->color1());
                            Session::put('platform.favicon', $platform->favicon());
                            Session::put('platform.short', $platform->short());
                        }
                    }
                } else
                    Session::put('user.administrator_id', $user->id);

                Session::put('user.email', $user->email);

                if ($user->profile) {
                    Session::put('user.language', $user->profile->language->getShort());
                    Session::put('user.language_id', $user->profile->language->getId());
                } else {
                    Session::put('user.language', 'en');
                    Session::put('user.language_id', 1);
                }

                Session::put('imonomy', FALSE);
                if ($user->publisher) {
                    Session::put('publisher.id', $user->publisher->getId());
                    Session::put('adserver.id', $user->publisher->getFirstAdserverId());
                    Session::put('publisher.show_alert', $user->publisher->getShowAlert()); // TRUE/FALSE
                    if ($user->publisher->imonomy) {
                        Session::put('imonomy', TRUE);
                    }
                } elseif (!$user->administrator) {
                    // averiguo si el usuario posee un adserver temporal
                    //$tmp_adserver = TmpAdserver::findByUserID($user->id);

                    /* if($tmp_adserver){
                      $adserver = $tmp_adserver->uta_adserver_id; */
                    if ($user->getAdserver()) {
                        $adserver = $user->getAdserver()->getId();
                    } else {
                        $adserver = Adserver::getDefault()->getId();
                    }

                    Session::put('adserver.id', $adserver);
                } else {
                    Session::put('adserver.id', Adserver::getDefault()->getId());
                }

                Session::put('user.completeData', Profile::completeData());
            }

            return TRUE;
        } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
            return ['error' => 1, 'message' => 'Ingrese un nombre de usuario'];
        } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            return ['error' => 2, 'message' => 'Ingrese una contraseña para el usuario'];
        } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
            return ['error' => 3, 'message' => 'Contraseña incorrecta, vuelve a intentarlo.'];
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return ['error' => 4, 'message' => 'El usuario no se ha encontrado.'];
        } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
            return ['error' => 5, 'message' => 'El usuario no está activado.'];
        } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
            return ['error' => 6, 'message' => 'La cuenta se encuentra suspendida.'];
        } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
            return ['error' => 7, 'message' => 'El usuario fué baneado del sistema.'];
        }
    }

    /*
     * Cierre de sesion del usuario en el sistema
     */

    public static function logout() {
        Session::flush();
        Sentry::logout();
    }

    /**
     * Registro de un nuevo usuario desde Admin
     */
    public static function addUser($data) {
        // se registra el usuario en la base de datos validando todo
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        try {
            $user = Sentry::register(array(
                        'email' => $data['user_email'],
                        'password' => $password
            ));
        } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
            return ['errors' => 1, 'message' => 'El correo electrónico ingresado ya se encuentra registrado'];
        }
        //Alta de perfil
        $profile = new Profile();
        $profile->setLanguage('en');
        $profile->setName($data['name']);
        $profile->setUser($user->id);
        $profile->forceSave();

        // se genera un codigo de activacion
        $activationCode = $user->getActivationCode();
        $url_activate = URL::to('activate') . '/' . $user->id . '/1/' . $activationCode . '/1';
        // datos para enviar por correo, utilizados en la vista
        $data = array(
            'user_id' => $user->id,
            'platform_id' => 1,
            'activation_code' => $activationCode,
            'activation_url' => $url_activate,
            'email' => $data['user_email'],
            'password' => $password
        );

        // se envia el correo para que el usuario pueda activar su cuenta
        Mailer::send('emails.auth.activate_admin', $data, $data['email'], '', 'Welcome to our freelancer affiliate program!');
        return ['errors' => 0, 'message' => $user->id];
    }

    /*
     * Registro de usuario
     *
     * @param array Datos ingresados en el formulario de registro
     */

    public static function register($data) {
    	
        try {
            if ($data['user_password'] !== $data['user_repeatPassword'])
                return ['error' => 4, 'message' => 'Las contraseñas no coinciden.'];
			
            // se registra el usuario en la base de datos validando todo
          // dd($data);	
            $user = Sentry::register(array(
                        'email' => $data['user_email'],
                        'password' => $data['user_password']
            ));
            
		
            //Alta de perfil
            //$profile = new Profile();
            //$profile->setLanguage('en');
            //$profile->setUser($user->id);
            //$profile->forceSave();

            // Asigno el ejecutivo de cuenta (media_buyer)
            if (empty($data['user_media_buyer'])) {
                $data['user_media_buyer'] = NULL;
            } elseif ($data['user_media_buyer'] == NULL) {
                $data['user_media_buyer'] = NULL;
            } else {
                $data['user_media_buyer'] = decrypt($data['user_media_buyer']);
            }

            // Asigno el adserver
            if (empty($data['user_adserver']))
                $data['user_adserver'] = Adserver::getDefault()->adv_id;

            // Asigno el adserver temporal al usuario
            $userModel = User::find($user->id);
            $userModel->setAdserver($data['user_adserver'], $data['user_media_buyer']);
            $userModel->setPlatform($data['user_platform']);

            /*
              $tmp_adserver = new TmpAdserver();
              $tmp_adserver->uta_user_id = $user->id;
              $tmp_adserver->uta_adserver_id = $data['user_adserver'];
              $tmp_adserver->save(); */

            // se genera un codigo de activacion
            $activationCode = $user->getActivationCode();
            $url_activate = URL::to('activate') . '/' . $user->id . '/' . $userModel->platform->id() . '/' . $activationCode;
            // datos para enviar por correo, utilizados en la vista
            $data = array(
                'user_id' => $user->id,
                'platform_id' => $userModel->platform->id(),
                'activation_code' => $activationCode,
                'activation_url' => $url_activate,
                'email' => $data['user_email']
            );

            // se envia el correo para que el usuario pueda activar su cuenta
            return Mailer::send('emails.auth.activate', $data, $data['email'], '', Lang::get('auth.welcomesubject'));
        } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
            return ['error' => 1, 'message' => Lang::get('auth.give_username')];
        } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            return ['error' => 2, 'message' => Lang::get('auth.give_password')];
        } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
            return ['error' => 3, 'message' => Lang::get('auth.email_already_used')];
        }
    }

    /*
     * Cambiar email de la cuenta del usuario
     *
     * @param int Id del usuario a cambiar el correo
     * @param string Nuevo correo electronico
     */

    public static function changeEmail($user_id, $email) {
        try {
            $user = Sentry::findUserById($user_id);

            $user->email = $email;
            //$user->activated = 0;

            Session::forget('error');

            try {
                // cambio el correo electronico del usuario
                if ($user->save()) {

                    Session::put('user.email', $email);

                    // se genera un codigo de activacion
                    $activationCode = $user->getActivationCode();

                    // datos para enviar por correo, utilizados en la vista
                    $data = array(
                        'user_id' => $user->id,
                        'activation_code' => $activationCode,
                        'activation_url' => URL::to('activate') . '/' . $user->id . '/' . $activationCode,
                        'email' => $email
                    );

                    // se envia el correo para que el usuario pueda activar su cuenta
                    return Mailer::send('emails.auth.activate', $data, $data['email'], '', 'Welcome back to our publisher program!');
                } else {
                    Session::put('error', $user);

                    return ['error' => 1, 'message' => $user];
                }
            } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
                return ['error' => 3, 'message' => 'El usuario ya existe en la base de datos.'];
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                return ['error' => 4, 'message' => 'No se encontró usuario.'];
            }
        } catch (Exception $ex) {
            if (Request::ajax())
                return ['error' => 2, 'message' => $ex->getMessage()];
        }
    }

    /*
     * Activacion de la cuenta
     *
     * @param int Id del usuario a activar
     * @param string Codigo de activacion
     */

    public static function activate($user_id, $code) {
        try {
// busco el usuario segun su ID
            $user = Sentry::findUserById($user_id);

// tratando de activar el usuario
            if (!$user->attemptActivation($code))
                return ['error' => 1, 'message' => 'El código de activación no es correcto.'];

// si la activacion paso correctamente
            return TRUE;
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return ['error' => 1, 'message' => 'No se encontró el usuario.'];
        } catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e) {
            return ['error' => 2, 'message' => 'El usuario ya se encuentra activado.'];
        }
    }

    /*
     * Genera un codigo para poder resetear la contrasena y envia al usuario
     * un correo electronico con un enlace para la generacion de la nueva contrasena
     *
     * @param string Email del usuario
     */

    public static function forgotPassword($user_email) {
        try {
// Busco el usuario en la base de datos
            $user = Sentry::findUserByLogin($user_email);

// Genero un nuevo codigo de reseteo de contrasena
            $resetCode = $user->getResetPasswordCode();

            $data = array(
                'user_id' => $user->id,
                'user_email' => $user_email,
                'resetPassword_code' => $resetCode,
                'resetPassword_url' => URL::to('reset_password') . '/' . $user->id . '/' . $resetCode,
            );

// envio email al usuario para que resetee su contrasena
            return Mailer::send('emails.auth.resetPassword', $data, $data['user_email'], '', 'Reset your password!');
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return ['error' => 1, 'message' => 'No se encontró el usuario.'];
        }
    }

    /*
     * Procesa el cambio de contrasena del usuario
     *
     * @param int    Id del usuario
     * @param string Codigo de activacion
     * @param string Contrasena nueva
     * @param string Repeticion de contrasena nueva
     */

    public static function resetPassword($user_id, $code, $password, $repeat_password) {
        try {
// Chequeo que la contrasena y la repeticion de la misma sean identicas
            if ($password !== $repeat_password)
                return ['error' => 1, 'message' => 'Las contraseñas no son identicas.'];

// Busco el usuario en la base de datos
            $user = Sentry::findUserById($user_id);

// Chequeo si el codigo de activacion corresponde
            if ($user->checkResetPasswordCode($code)) {
                // Intenta restablecer la contraseña de usuario
                if (!$user->attemptResetPassword($code, $password))
                    return ['error' => 3, 'message' => 'No se pudo reestablecer la contraseña.'];

                return TRUE;
            } else {
                // El codigo de restablecimiento de contrasena proporcionado no es valido
                return ['error' => 4, 'message' => 'El codigo de restablecimiento de contrasena proporcionado no es valido.'];
            }
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return ['error' => 2, 'message' => 'No se encontró el usuario.'];
        }
    }

    public static function rewriteSessionData($user) {
        Session::put('user.id', $user->id);
        Session::put('user.email', $user->email);

        if ($user->profile) {
            Session::put('user.language', $user->profile->language->getShort());
        } else {
            Session::put('user.language', 'en');
        }

        if ($user->publisher) {
            if ($user->publisher->imonomy) {
                Session::put('imonomy', TRUE);
            }
            Session::put('publisher.id', $user->publisher->getId());
            Session::put('adserver.id', $user->publisher->getFirstAdserverId());
        } else {
            Session::put('adserver.id', Adserver::getDefault()->adv_id);
        }

        Session::put('user.completeData', Profile::completeData());
    }

}
