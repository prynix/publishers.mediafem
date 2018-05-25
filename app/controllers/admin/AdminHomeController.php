<?php

class AdminHomeController extends BaseController {
    /*
     * Muestra la pantalla de inicio de la herramienta
     */

    public function getIndex() {
        $adservers = Adserver::all();
        $media_buyer = Administrator::find(Session::get('admin.id'));
        $adservers_media_buyer = $media_buyer->adservers;
        return View::make('admin.home.index', ['adservers' => $adservers, 'adservers_media_buyer' => $adservers_media_buyer]);
    }
    
    public function getHelp() {
        return View::make('admin.help.index');
    }


}
