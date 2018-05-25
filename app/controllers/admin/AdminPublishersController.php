<?php

class AdminPublishersController extends BaseController {
    /*
     *
     * GETs
     *  
     */

    /*
     * Muestra la pantalla de mis pagos
     */

    public function getIndex() {
        //Api::adjustRevShare(2, PublisherOptimization::find(34));
        //$D = new OAuthToken();
        return View::make('admin.publishers.index');
    }

    public function loadPublishersTable() {
        if (Utility::hasPermission('publishers.all'))
            $publishers = DB::table('publishers_simple_view')->get();
        else
            $publishers = DB::table('publishers_simple_view')->where('media_buyer_id', Session::get('admin.id'))->orderBy('id', 'desc')->get();
        return View::make('admin.tables.tbl_publishers', ['publishers' => $publishers]);
    }

    public function getPublisherView($id) {
        $publisher = Publisher::find($id);
        return View::make('admin.publishers.show', ['publisher' => $publisher]);
    }

    public function getTaxForm($file_name) {
        return Response::download(public_path() . '/tax_data/' . $file_name);
    }

    public function getExport() {
        if (Utility::hasPermission('publishers.all'))
            $publishers = DB::table('publishers_complete_view')->get();
        else {
            $publishers = DB::table('publishers_complete_view')
                    ->where('media_buyer_id', Session::get('admin.id'))
                    ->orderBy('id', 'desc')
                    ->get();
        }
        header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"adtomatik_publishers.xls\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        return View::make('admin.export.publishers', ['publishers' => $publishers]);
    }

    /*
     *
     * SETs
     *  
     */

    public function hideAlertAndDeleteYax($id_publisher) {
        try {
            $publisher = Publisher::find($id_publisher);
            $alert = Input::get('alert');
            if ($alert == '0') {
                $publisher->hideAlertAndDeleteYax();
            }
            return Response::json(['error' => 0]);
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 1, 'messages' => $ex]);
            return Redirect::back();
        }
    }

    public function updateAccountData($id_usuario) {
        try {
            // buscamos el perfil del usuario
            $user = User::find($id_usuario);
            
            $user->platform_id = Input::get('platform_id');
            $user->forceSave();
            
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

            if (!$profile->validate()) {
                Session::put('error', $profile->errors());

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $profile->errors()]);
                return Redirect::back();
            }

            $profile->save();

            $publisher->pbl_days_to_billing = Input::get('pbl_days_to_billing');
            $publisher->pbl_revenue_share = Input::get('pbl_revenue_share');
            $publisher->pbl_has_to_optimize = Input::get('pbl_has_to_optimize');
            $publisher->save();
            
            
           
            
            
            
            if (Request::ajax())
                return Response::json(['error' => 0]);

            return Redirect::back();
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

    public function setMediaBuyer() {
        try {
            if ((int) Input::get('mediaBuyerId') == 0) {
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => 'Debe seleccionar un ejecutivo']);
                return Redirect::back();
            }
            $publisher = Publisher::find(Input::get('publisherId'));
            $publisher->setMediaBuyer((int) Input::get('mediaBuyerId'));

            if (!$publisher->forceSave()) {
                Session::put('error', $publisher->errors());

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $publisher->errors()]);
                return Redirect::back();
            }

            Api::assignMediaBuyer($publisher->getFirstAdserverId(), $publisher);

            return Response::json(['error' => 0]);
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

    public function saveImonomy() {
        try {
            if ((int) Input::get('publisherId') == 0) {
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => 'Error interno, intente nuevamente.']);
                return Redirect::back();
            }
            $publisher = Publisher::find(Input::get('publisherId'));
            
            if ($publisher->imonomy) {
                if (Input::get('imonomy_publisher_id')) {
                    $publisher->imonomy->setImonomyId(Input::get('imonomy_publisher_id'));
                    $publisher->imonomy->save();
                } else {
                    $publisher->imonomy->delete();
                }
            } else {
                $imonomy = new ImonomyPublisher();
                $imonomy->setPublisher($publisher->getId());
                $imonomy->setImonomyId(Input::get('imonomy_publisher_id'));
                $imonomy->save();
                $publisher = Publisher::find(Input::get('publisherId'));
            }

            $cambios = FALSE;
            foreach ($publisher->sites as $site) {
                if ($site->imonomy) {
                    if (Input::get('imonomy_site_' . $site->getId()) || Input::get('imonomy_tag_' . $site->getId())) {
                        $site->imonomy->setImonomyId(Input::get('imonomy_site_' . $site->getId()));
                        $site->imonomy->setImonomyTag(Input::get('imonomy_tag_' . $site->getId()));
                        $site->imonomy->save();
                        $cambios = TRUE;
                    } else {
                        $site->imonomy->delete();
                    }
                } else {
                    if (Input::get('imonomy_site_' . $site->getId()) || Input::get('imonomy_tag_' . $site->getId())) {
                        $imonomy = new ImonomySite();
                        $imonomy->setsite($site->getId());
                        $imonomy->setImonomyId(Input::get('imonomy_site_' . $site->getId()));
                        $imonomy->setImonomyTag(Input::get('imonomy_tag_' . $site->getId()));
                        $imonomy->save();
                        $cambios = TRUE;
                    }
                }
            }
            
            if ($cambios && !$publisher->imonomy)
                return Response::json(['error' => 1, 'messages' => 'Debe completar el Id de Publisher de Imonomy']);
            
             return Response::json(['type' => 1, 'message' => 'Los cambios se han guardado correctamente!']);
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

    public function assingTester($publisherId) {
        $admin = User::find(Session::get('user.id'))->administrator;

        if ($admin->publisherTester) {
            if ($publisherId == $admin->publisherTester->getId()) {
                return View::make('admin.general.message', ['type' => 2, 'message' => 'El publisher ya se encuentra asignado a su cuenta']);
            }
        }
        try {
            $user = User::find(Session::get('user.id'));
            $user->administrator->setPublisherTester($publisherId);
            $user->administrator->forceSave();
            return View::make('admin.general.message', ['type' => 1, 'message' => 'El publisher fue asignado OK!']);
        } catch (Exception $ex) {
            return View::make('admin.general.message', ['type' => 3, 'message' => 'Ha surgido un error!']);
        }
    }

}
