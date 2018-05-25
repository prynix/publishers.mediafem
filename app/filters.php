<?php

/*
  |--------------------------------------------------------------------------
  | Application & Route Filters
  |--------------------------------------------------------------------------
  |
  | Below you will find the "before" and "after" events for the application
  | which may be used to do any work before or after a request into your
  | application. Here you may also register your custom route filters.
  |
 */

App::before(function($request) {
    /* if (Request::path() === 'login' ||
      Request::path() === 'register' ||
      Request::segment(1) === 'activate' ||
      Request::path() === 'forgot_password' ||
      Request::segment(1) === 'reset_password') {
      if (Sentry::check())
      return Redirect::to('/');
      } else {
      if (Sentry::check() === FALSE)
      return Redirect::to('login');

      App::setLocale(Session::get('user.language'));
      } */
	if (Input::has('lang')){
		App::setLocale(Input::get('lang'));
	} 
	 
});


App::after(function($request, $response) {
//
});

/*
  |--------------------------------------------------------------------------
  | Authentication Filters
  |--------------------------------------------------------------------------
  |
  | The following filters are used to verify that the user of the current
  | session is logged into this application. The "basic" filter easily
  | integrates HTTP Basic authentication for quick, simple checking.
  |
 */

Route::filter('authPublisher', function() {
    try {
        if (Sentry::check() === FALSE) {
            return Redirect::to('webpageindex');
        } else {
//Se fija si el usuario logueado es un publisher o Admin con tester asignado
            $user = User::find(Session::get('user.id'));
            if(!$user){
                return Redirect::to('login');
            }
            if (!$user->isAdministrator())
                App::setLocale(Session::get('user.language'));
            elseif ($user->administrator->publisherTester) {
                Pluton::rewriteSessionData($user->administrator->publisherTester->user);
                Session::put('user.admin.id', $user->getId());
            } else
                return Redirect::to('login');
        }
    } catch (Exception $ex) {
        return Redirect::to('admin/login');
    }
});

Route::filter('authAdmin', function() {
    try {
        if (Sentry::check() === FALSE) {
            return Redirect::to('admin/login');
        } else {
            $user = User::find(Session::get('user.id'));
            if(!$user){
                return Redirect::to('admin/login');
            }
            if ($user->isAdministrator())
                App::setLocale(Session::get('user.language'));
            elseif (Session::get('user.admin.id') !== null) {
                $admin = User::find(Session::get('user.admin.id'));
                Pluton::rewriteSessionData($admin);
                App::setLocale(Session::get('user.language'));
            } else
                return Redirect::to('admin/login');
        }
    } catch (Exception $ex) {
        return Redirect::to('admin/login');
    }
});

/*Route::filter('showReports', function() {
    try {
        if(Session::get('publisher.show_alert') == 1){
            Redirect::to('/');
        }else{
            return true;
        }
    } catch (Exception $ex) {
        return Redirect::to('login');
    }
});*/


Route::filter('auth.basic', function() {
    return Auth::basic();
});

/*
  |--------------------------------------------------------------------------
  | Guest Filter
  |--------------------------------------------------------------------------
  |
  | The "guest" filter is the counterpart of the authentication filters as
  | it simply checks that the current user is not logged in. A redirect
  | response will be issued if they are, which you may freely change.
  |
 */

Route::filter('guest', function() {
    if (Auth::check())
        return Redirect::to('/');
});

/*
  |--------------------------------------------------------------------------
  | CSRF Protection Filter
  |--------------------------------------------------------------------------
  |
  | The CSRF filter is responsible for protecting your application against
  | cross-site request forgery attacks. If this special token in a user
  | session does not match the one given in this request, we'll bail.
  |
 */

Route::filter('csrf', function() {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});


/**
 * Control de permiso.
 * En caso de no tener el permiso para acceder al link redirige a la raiz
 */
Route::filter('permission', function($route, $request, $tab) {
    if (!Utility::hasPermission($tab)) {
        return Redirect::to('/admin');
    }
});
