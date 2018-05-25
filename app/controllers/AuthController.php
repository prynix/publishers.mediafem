<?php

class AuthController extends BaseController {
    /*
     * Muestra el formulario de Ingreso
     */

    public function getLogin() {
        $platform = Platform::find(1);
        if (Pluton::server())
            return View::make('auth.login', ['messages' => FALSE, 'platform' => $platform]);
		
        return Redirect::to('http://adtomatik.com/publishers');
    }

    /*     * *
     * LogIn para MediaFem
     */

    public function getLoginMF() {
        $platform = Platform::find(2);
        if (Pluton::server())
            return View::make('auth.login', ['messages' => FALSE, 'platform' => $platform]);

        return Redirect::to('https://www.mediafem.com/sitiosweb/?redirect=no');
    }

    /*
     * Procesa el formulario de ingreso
     */

    public function setLogin() {
        // asigno los datos ingresados
        $data = array(
            'user_email' => Input::get('email'),
            'user_password' => Input::get('password'),
            'user_remember' => Input::get('remember')
        );

        $login = Pluton::login($data);

        if (isset($login['error'])) {
            // si ocurrio un error al momento de registrarse
            $return['messages'] = $login;
            $return['platform'] = Platform::find(Input::get('platform'));
            if (Pluton::server())
                return View::make('auth.login', $return);

            return Redirect::to('http://adtomatik.com/publishers');
        } else {
            if (Session::get('user.administrator_id') || Session::get('publisher.id')) {
                // si se registro correctamente y puede acceder a Publishers
                return Redirect::to('/');
            }else{
                return Redirect::to('/admin');
            }
        }
    }

    /*
     * Cierra la sesion del usuario en el sistema
     */

    public function getLogout() {
        Pluton::logout();

        if (Pluton::server()) {
            if (null !== Session::get('platform.name')) {
                if (Session::get('platform.name') == 'mediafem')
                    return Redirect::to('/login_mf');
            }
            return Redirect::to('/login');
        }
        return Redirect::to('/login');
        if (null !== Session::get('platform.name')) {
            if (Session::get('platform.name') == 'mediafem')
                return Redirect::to('https://www.mediafem.com/sitiosweb/?redirect=no');
        }
        return Redirect::to('http://adtomatik.com/publishers');
    }

    /*
     * Muestra el formulario de Registro
     */

    public function getRegister($adserver = NULL, $mediabuyer = NULL) {
    	$platform = Platform::find(Config::get('app.default_platform'));

	try {
            $decrypted = decrypt($mediabuyer);
        } catch (Exception $ex) {
            $decrypted = NULL;
        }
        if ($mediabuyer) {
            $admin = Administrator::find($decrypted);
            if ($admin) {
                if (count($admin->adservers) > 0) {
                    foreach ($admin->adservers as $adserver_admin) {
                        if ($adserver_admin->getId() == $adserver) {
                            // Media buyer y adserver correctos
                            return View::make('auth.register', ['messages' => FALSE, 'adserver' => $adserver, 'platform' => $platform, 'media_buyer' => $mediabuyer]);
                        }
                    }
                    // Media buyer correcto y adserver incorrecto
                    return Redirect::to('/register/' . $admin->adservers[0]->getId() . '/' . $mediabuyer);
                }
                $ads = Adserver::find($adserver);
                if ($ads) {
                    return Redirect::to('/register/' . $ads);
                } else {
                    return Redirect::to('/register');
                }
            }
            $ads = Adserver::find($adserver);
            if ($ads) {
                return Redirect::to('/register/' . $ads->getId());
            }
            return Redirect::to('/register');
        }
        $ads = Adserver::find($adserver);
        if ($ads || !$adserver) {
            return View::make('auth.register', ['messages' => FALSE, 'adserver' => $adserver, 'platform' => $platform, 'media_buyer' => $mediabuyer]);
        }
        return Redirect::to('/register');
    }

    /*
     * Muestra el formulario de Registro
     */

    public function getRegisterMF($adserver = NULL) {
        $platform = Platform::find(2);
        return View::make('auth.register', ['messages' => FALSE, 'adserver' => $adserver, 'platform' => $platform]);
    }

    /*
     * Procesa el formulario de registro
     */

    public function setRegister() {

      	$platform = Platform::find(Config::get('app.default_platform'));
        /* $data2 = ['recaptcha_response_field' => Input::get('recaptcha_response_field')];

          $v = Validator::make($data2, array(
          'recaptcha_response_field' => 'required|recaptcha',
          ));

          if ($v->fails()) {
          $return['messages']['message'] = Lang::get('validation.recaptcha', ['attribute' => 'CAPTCHA']);
          $return['adserver'] = Input::get('adserver');
          $return['platform'] = $platform;

          return View::make('auth.register', $return);
          } else { */
        // asigno los datos ingresados
        $data = array(
            'user_email' => Input::get('email'),
            'user_password' => Input::get('password'),
            'user_repeatPassword' => Input::get('repeatPassword'),
            'user_adserver' => Input::get('adserver'),
            'user_platform' => Input::get('platform'),
            'user_media_buyer' => Input::get('media_buyer')
        );

        // proceso el registro
        $register = Pluton::register($data);

        if (isset($register['error'])) {
            // si ocurrio un error al momento de registrarse
            //return View::make('auth.register', ['messages' => $register, 'adserver' => $data['user_adserver'], 'platform' => $platform, 'media_buyer' => Input::get('media_buyer')]);
            return Redirect::to('/register/' . Input::get('adserver') . '/' . Input::get('media_buyer'))->with('messages', $register);
        } else {
            // si se registro correctamente
            if (Pluton::server())
                return View::make('auth.register_ok', ['platform', $platform]);

            return Redirect::to('http://adtomatik.com/publishers?a=R');
        }
        //}
    }

    /*
     * Procesa la activacion de la cuenta del usaurio
     */

    public static function setActivate($user_id, $platform, $code, $is_admin = 0) {
        $admin = "";
        if ($is_admin == 1)
            $admin = "admin.";
        // proceso la activacion del usuario
        $register = Pluton::activate($user_id, $code);
        $platform = Platform::find($platform);
        if (isset($register['error'])) {
            // si ocurrio un error al momento de registrarse
            $return['messages'] = $register;
            $return['platform'] = $platform;

            if (Pluton::server())
                return View::make($admin . 'auth.activate', $return);

            return Redirect::to('http://adtomatik.com/publishers?a=I');
        } else {
            // si se registro correctamente
            if (Pluton::server())
                return View::make($admin . 'auth.activate_ok', ['platform' => $platform]);

            return Redirect::to('http://adtomatik.com/publishers?a=A');
        }
    }

    /*
     * Envia al usuario un correo electronico para que resetee su contrasena
     */

    public function setForgotPassword() {
        // proceso el registro
        $resetPassword = Pluton::forgotPassword(Input::get('email'));

        if (isset($resetPassword['error'])) {
            // si ocurrio un error al momento de registrarse
            $return['messages'] = $resetPassword;

            return $return;
        } else {
            // si se registro correctamente
            if (Pluton::server())
                return View::make('auth.forgotPassword_ok');

            return Redirect::to('http://adtomatik.com/publishers?a=P');
        }
    }

    /*
     * Muestra el formulario de reseteo de contrasena
     */

    public function getResetPassword($user_id, $code) {
        // si el codigo es el correcto muestro formulario
        return View::make('auth.resetPassword', ['user_id' => $user_id, 'code' => $code, 'messages' => FALSE]);
    }

    /*
     * Procesa el formulario de reseteo de contrasena
     */

    public function setResetPassword() {
        // obtengo los datos ingresados en el formulario
        $data = array(
            'password' => Input::get('password'),
            'repeat_password' => Input::get('repeat_password'),
            'code' => Input::get('code'),
            'user_id' => Input::get('user_id')
        );

        // intento resetear la contrasena
        $resetPassword = Pluton::resetPassword($data['user_id'], $data['code'], $data['password'], $data['repeat_password']);

        if (isset($resetPassword['error'])) {
            // si ocurrio un error al momento de resetear la contrasena
            return View::make('auth.resetPassword', ['user_id' => $data['user_id'], 'code' => $data['code'], 'messages' => $resetPassword]);
        } else {
            // si la contrasena se reseteo correctamente correctamente
            return View::make('auth.resetPassword_ok');
        }
    }

}
