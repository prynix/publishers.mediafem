<?php

class AdminAuthController extends BaseController {
    /*
     * Muestra el formulario de Ingreso
     */

    public function getLogin() {
        return View::make('admin.auth.login', ['messages' => FALSE]);
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

            return View::make('admin.auth.login', $return);
        } else {
            // si se registro correctamente
            return Redirect::to('/admin');
        }
    }

    /*
     * Cierra la sesion del usuario en el sistema
     */

    public function getLogout() {
        Pluton::logout();

        return Redirect::to('/admin/login');
    }

    /*
     * Muestra el formulario de Registro
     */

    /*public function getRegister() {
        return View::make('admin.auth.register', ['messages' => FALSE]);
    }*/

    /*
     * Procesa el formulario de registro
     */

    /*public function setRegister() {

        $data2 = ['recaptcha_response_field' => Input::get('recaptcha_response_field')];

        $v = Validator::make($data2, array(
                    'recaptcha_response_field' => 'required|recaptcha',
                ));

        if ($v->fails()) {
            $return['messages']['message'] = Lang::get('validation.recaptcha', ['attribute' => 'CAPTCHA']);

            return View::make('admin.auth.register', $return);
        } else {
            // asigno los datos ingresados
            $data = array(
                'user_email' => Input::get('email'),
                'user_password' => Input::get('password'),
                'user_repeatPassword' => Input::get('repeatPassword')
            );

            // proceso el registro
            $register = Pluton::register($data);

            if (isset($register['error'])) {
                // si ocurrio un error al momento de registrarse
                $return['messages'] = $register;

                return View::make('admin.auth.register', $return);
            } else {
                // si se registro correctamente
                return View::make('admin.auth.register_ok');
            }
        }
    }*/

    /*
     * Procesa la activacion de la cuenta del usaurio
     */

   /* public static function setActivate($user_id, $code) {
        // proceso la activacion del usuario
        $register = Pluton::activate($user_id, $code);

        if (isset($register['error'])) {
            // si ocurrio un error al momento de registrarse
            $return['messages'] = $register;

            return View::make('admin.auth.activate', $return);
        } else {
            // si se registro correctamente
            return View::make('admin.auth.activate_ok');
        }
    }*/

    /*
     * Envia al usuario un correo electronico para que resetee su contrasena
     */

   /* public function setForgotPassword() {
        // proceso el registro
        $resetPassword = Pluton::forgotPassword(Input::get('email_forgot'));

        if (isset($resetPassword['error'])) {
            // si ocurrio un error al momento de registrarse
            $return['messages'] = $resetPassword;

            return $return;
        } else {
            // si se registro correctamente
            return View::make('admin.auth.forgotPassword_ok');
        }
    }*/

    /*
     * Muestra el formulario de reseteo de contrasena
     */

   /* public function getResetPassword($user_id, $code) {
        // si el codigo es el correcto muestro formulario
        return View::make('admin.auth.resetPassword', ['user_id' => $user_id, 'code' => $code, 'messages' => FALSE]);
    }*/

    /*
     * Procesa el formulario de reseteo de contrasena
     */

  /*  public function setResetPassword() {
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
            return View::make('admin.auth.resetPassword', ['user_id' => $data['user_id'], 'code' => $data['code'], 'messages' => $resetPassword]);
        } else {
            // si la contrasena se reseteo correctamente correctamente
            return View::make('admin.auth.resetPassword_ok');
        }
    }*/

}