<?php

class PlacementController extends BaseController {
    /*
     * Muestra la pantalla de inicio de la herramienta
     */

    public function getIndex() {
        Session::forget('error');

        // tomo los sitios del publisher
        $sites = self::getSites(Session::get('publisher.id'));

        $sizes = Size::getAdserverSizes(Session::get('adserver.id'));



        return View::make('placement.index', ['sites' => $sites, 'sizes' => $sizes]);
    }

    /*
     * muestra el listado de espacios del sitio solicitado
     */

    public function getPlacementsView($id_site) {
        $site = Site::find($id_site);
        $sizes = Size::getAdserverSizes(Session::get('adserver.id'));
        $placements = NULL;
        $new_placement = FALSE;
        if ($site->sit_is_validated) {
            if (Session::get('adserver.id') == 1)
                $placements = $site->sectionsLikePlacements(); //Section::getSectionBySite($id_site);
            else
                $placements = $site->placements; //self::getPlacements($id_site);
            $adserver_id = Session::get('adserver.id');
            if (($adserver_id == '2') || ($adserver_id == '3') && ($site->publisher->getId() !== 2639))
                $new_placement = TRUE;
        }
        return View::make('placement.list', ['sizes' => $sizes, 'site' => $site, 'placements' => $placements, 'adserver' => Session::get('adserver.id'), 'new_placement' => $new_placement]);
    }

    /*
     * Devuelve todos los sitios de un publisher
     */

    public static function getSites($publisher_id) {
        return Site::getSitesByPublisher($publisher_id);
    }

    /*
     * Devuelve los placements del sitio solicitado
     */

    public static function getPlacements($id_site) {
        // traigo todos los placements del sitio
        $placements = Placement::getPlacementsBySite($id_site);

        // asigno el nombre del tamaÃ±o
        foreach ($placements as $placement) {
            $size = Size::find($placement->plc_size_id);
            $placement->plc_size = $size;
        }

        return $placements;
    }

    /*
     * Genera el codigo de un placement
     */

    public static function getPlacementCode($adserverKey, $siteName, $placementName, $placementAdserverName, $size, $height, $width, $aditionalKey, $formatName) {
        $adserver = Adserver::find(Session::get('adserver.id'));
        $adserverName = $adserver->getClassName();
        $adserverUser = $adserver->getUsername();
        echo htmlentities(getTags($adserverName, ['adserverKey' => $adserverKey, 'siteName' => $siteName, 'placementName' => $placementName, 'placementAdserverName' => $placementAdserverName, 'size' => $size, 'height' => $height, 'width' => $width, 'adNetwork' => $adserverUser, 'aditionalKey' => $aditionalKey], $formatName));
    }

    /*
     * Genera el codigo de todos los placements
     */

    public static function getAllPlacementsCodes($site_id) {
        try {
            $allCodes = "";
            $adserver = Adserver::find(Session::get('adserver.id'));
            $site = Site::find($site_id);
            $adserverName = $adserver->getClassName();
            $adserverUser = $adserver->getUsername();

            foreach ($site->placements as $placement) {
                if ($placement->getAditionalAdserverKey() == NULL) {
                    $aditionalKey = 0;
                } else {
                    $aditionalKey = $placement->getAditionalAdserverKey();
                }
                $allCodes = $allCodes . htmlentities(getTags($adserverName, ['adserverKey' => $placement->getKey(), 'siteName' => $placement->site->getName(), 'placementName' => str_replace(' ', '', $placement->getName()), 'placementAdserverName' => str_replace(' ', '', $placement->getAdserverName()), 'size' => $placement->size->getName(), 'height' => $placement->size->getHeight(), 'width' => $placement->size->getWidth(), 'adNetwork' => $adserverUser, 'aditionalKey' => $aditionalKey], strtolower(str_replace(' ', '', $placement->size->sizeType->getName()))) . "\n\n");
            }
            return $allCodes;
        } catch (Exception $ex) {
            return "Internal Error.";
        }
    }

    /*
     * Genera el codigo del formato in image de Imonomy
     */

    public static function getImonomyCode($site_id) {
        try {

            $site = Site::find($site_id);
            $code = htmlentities(getImonomyTag($site->getName(), $site->imonomy->getImonomyTag()));

            return $code;
        } catch (Exception $ex) {
            return "Internal Error.";
        }
    }

    /*
     * Crea un sitio en la Base de datos
     */

    public function createSite() {
        try {
            $site = new Site();
            $site->fill(Input::all());

            $site->setPublisher(Session::get('publisher.id'));

            Session::forget('error');

            if (!$site->save()) {
                Session::put('error', $site->errors());

                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $site->errors()]);
                return Redirect::back();
            }

            if (Request::ajax())
                return Response::json(['error' => 0, 'sit_id' => $site->getId(), 'sit_name' => $site->getName()]);

            return Redirect::back();
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

    public function getPlacementName($siteId, $sizeId) {
        $site = Site::find($siteId);
        $size = Size::find($sizeId);
        $count = 0;
        foreach ($site->placements as $placement) {
            if ($placement->size->getId() == $sizeId)
                $count = $count + 1;
        }
        $name = $site->getName() . '-' . $size->getName();
        if ($count > 0)
            $name = $name . '-' . ($count + 1);
        return $name;
    }

    /*
     * Crea un anuncio en la Base de datos
     */

    public function createPlacement() {
        try {
            
            $placement = new Placement();
            
            $placement->fill(Input::all());
            $placement->setSite(Input::get('plc_site_id'));
            $placement->setSize(Input::get('plc_size_id'));
            $placement->setName(Input::get('plc_name'));
            
            Session::forget('error');
            
            if (!$placement->save()) {
                
                Session::put('error', $placement->errors());
                
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $placement->errors()]);
                return Redirect::back();
            }
            
            if(Input::get('plc_size_id') == 11){
                //Create Video
                $bridVideo = new BridVideo();
                $bridVideo->setPlacement($placement->plc_id);
                $bridVideo->setUrl(Input::get('plc_url_video'));
                
                if(!$bridVideo->save())
                    return Response::json(['error' => 2, 'messages' => Lang::get('validation.curl_url')]);
                
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
     * Descarga un archivo HTML para la validacion de un sitio
     */

    public function downloadVerificationFile($id_site) {
        return View::make('placement.site_validate_html_file', ['id_site' => base64_encode($id_site)]);
    }

    /*
     * valida un sitio con tag o con descarga de archivo
     */

    public function validateSite() {
        try {
            $site_id = Input::get('sit_id');
            $site_name = Input::get('sit_name');
            $method = Input::get('validate_method');
            $methodType = 0;
            
            $platform_name = Session::get('platform.name');
            $platform_brand = Session::get('platform.brand');
            
            // leo los metadatos para buscar el tag de Adtomatik
            if ($method == 'file') {
                $tags = get_meta_tags('http://' . $site_name . '/'.$platform_brand.'_validate_site.html');
                $methodType = 1;
            } else if ($method == 'tag') {
                $tags = get_meta_tags('http://' . $site_name);
                $methodType = 2;
            }
            
            // Si el tag no esta
            if (!isset($tags[$platform_name.'-tag'])) {
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => Lang::get('placements.error_validar_sitio')]);
                return Redirect::back();
            }

            // si son diferentes tags
            if ($tags[$platform_name.'-tag'] != base64_encode($site_id)) {
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => Lang::get('placements.error_codigo_sitio')]);
                return Redirect::back();
            }

            
            self::setApprovedPublisherAndValidatedSite($methodType, Site::find($site_id));

            // devuelvo que todo salio OK wachin!
            if (Request::ajax())
                return Response::json(['error' => 0]);
            return Redirect::back();
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 1, 'messages' => $ex->getMessage()]);
            //return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

    /*
     * Ingresa en la base de datos un listado de dominios de sitios
     */

    public function setDomainList() {
        try {

            Session::forget('error');
            // busco el sitio a modificar
            $site = Site::find(Input::get('sit_id'));

            //ValidaciÃ³n a la lista de dominios
            $dominios = $site->validateDomainList(Input::get('domain_list'));
            if (count($site->errors()) > 0) {
                Session::put('error', $site->errors());

                //Errores post validacion de dominios
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $site->validationErrors->first('sit_name')]);
                return Redirect::back();
            }
            $site->setDomainList($dominios);

            if (!$site->forceSave()) {
                Session::put('error', $site->errors());

                //Errores post save()
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $site->validationErrors->first('sit_name')]);
                return Redirect::back();
            }
            self::setApprovedPublisherAndValidatedSite(3, $site);

            if (Request::ajax())
                return Response::json(['error' => 0]);

            return Redirect::back();
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 1, 'messages' => $ex->getMessage()]);
            //return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

    /*
     * Creo el sitio en el adserver
     */

    private function setApprovedPublisherAndValidatedSite($validationType, $site) {
        try {
            // creo el sitio en el adserver
            self::createSiteInAdServer($site->getId(), $site->getName());
            //echo 'Creado en el server';
            // marco como validado el sitio            
            $site->setValidated(1);

            $site->setValidationType($validationType);
            $site->forceSave();

            // envio mail al ejecutivo o a media@mediafem.com avisando
            // el registro de nuevo sitio
            $adserver = Adserver::find(Session::get('adserver.id'));
            if ($site->publisher->mediaBuyer) {
                $to = $site->publisher->mediaBuyer->user->getEmail();
                //else
                //$to = 'media@mediafem.com';
                Mailer::send('emails.alert.newSite', ['adserverName' => $adserver->getName(), 'adserverKey' => $site->getAdserverKey($adserver->getId()), 'site' => $site], $to, 'Media Buyer', 'Publisher tiene un Sitio Nuevo');
            }
            // si todo salio bien marco como validado el publisher  
            $publisher = $site->publisher;
            $publisher->setApproved(1);
            $publisher->forceSave();
            //echo 'vuelve true '; 
            return TRUE;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    private function createSiteInAdServer($site_id, $site_name) {
        // selecciono el sitio a crear
        $site = Site::find($site_id);

        try {
            // creo el sitio en el adserver
            $site->createSiteInAdServer();
        } catch (Exception $ex) {
            $error = Lang::get('codes.banned', ['mediaBuyerEmail' => ($site->publisher->mediaBuyer ? $site->publisher->mediaBuyer->user->getEmail() : 'info@adtomatik.com')]);
            throw new Exception($error);
        }

        // asigno las categorias default
        $categories = array();
        $categories = Category::getAdserverDefaultCategories(Session::get('adserver.id'));
        if ($categories) {
            $site->categories()->saveMany($categories);
            Site::updateSiteSetCategorizedFalse($site->getId());
        }

        $adserver = Adserver::find(Session::get('adserver.id'));
       
        if ($adserver->getClassName() == 'YaxApi') {
            // creo una seccion para el sitio            
            $section = new Section();
            $section->setName($site->getName());
            $section->setSite($site->getId());
            $section->save();
        } else {
            // creo los placements en la base de datos
            // leo todos los tamanos de espacios activos que existen actualmente
            $sizes = Size::getAdserverSizes(Session::get('adserver.id'));

            foreach ($sizes as $size) {
                if($size->getId() == 11 || $size->getId() == 10 || $size->getId() == 9 || $size->getId() == 6)
                    continue;
                
                $placement = new Placement();
                $placement->setName($site->getName() . '-' . $size->getName());
                $placement->setSize($size->getId());
                $placement->setSite($site->getId());

                $placement->save();
            }
        }
    }

}
