<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Api
 *
 * @author Valeria
 */
class Api {

    private static function getAdserverClassName($id) {
        $adserver = Adserver::find($id);
        return $adserver->getClassName();
    }

    public static function __callStatic($method, $parameters) {
        // Validate if token did not expire
        self::validateToken($parameters[0]);

        // Create a new instance of the called class, in this case it is Post
        $model = get_called_class();

        // Call the requested method on the newly created object
        return call_user_func_array(array(new $model, $method), $parameters);
        //parent::__callStatic($method, $parameters);
    }

    /*     * *
     * @param integer $idAdserver, Publisher $publisher
     */

    protected static function newPublisher($idAdserver, $publisher) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->newPublisher($publisher);
    }

    protected static function getOrderReport($idAdserver, $lineitem) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->getOrderReport($lineitem);
    }

    protected static function excludeAdunitsFromLineitem($idAdserver, $lineitem_id, $adunits) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->excludeAdunitsFromLineitem($lineitem_id, $adunits);
    }

    protected static function addDefaultTagToPlacement($idAdserver, $placement) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->addDefaultTagToPlacement($placement);
    }

    protected static function changeSiteOptions($idAdserver, $site) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->changeSiteOptions($site);
    }

    protected static function getOrders($idAdserver) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->getOrders();
    }

    /*     * *
     * @param integer $idAdserver, string $publisher
     */

    protected static function assignPaymentRule($idAdserver, $publisher) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->assignPaymentRule($publisher);
    }

    protected static function getMediaBuyerReport($idAdserver, $administrator, $range = NULL) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->getMediaBuyerReport($administrator, $range);
    }

    /*     * *
     * @param integer $idAdserver, Publisher $publisher
     */

    protected static function getDefaultSectionKeyBySite($idAdserver, $site) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->getDefaultSectionKeyBySite($site->getAdserverKey($idAdserver));
    }

    /*     * *
     * @param integer $idAdserver, Site $site
     */

    protected static function getPublishersOptimization($idAdserver, $publisher_keys) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->getPublishersOptimization($publisher_keys);
    }

    /*     * *
     * @param integer $idAdserver, Site $site
     */

    protected static function getPaymentRuleId($idAdserver, $publisher_keys) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->getPaymentRuleId($publisher_keys);
    }

    /*     * *
     * @param integer $idAdserver, PublisherOptimization $publisherOptimization
     */

    protected static function adjustRevShare($idAdserver, $publisherOptimization) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->adjustRevShare($publisherOptimization);
    }

    /*     * *
     * @param integer $idAdserver, Site $site
     */

    protected static function newSite($idAdserver, $site) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->newSite($site);
    }

    /*     * *
     * @param integer $idAdserver, Site $site
     */

    protected static function categorizeSite($idAdserver, $site) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->categorizeSite($site);
    }

    /*     * *
     * @param integer $idAdserver, Array('site_id') $sites
     */

    protected static function categorizeSites($idAdserver, $sites) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->categorizeSites($sites);
    }

    protected static function newCategory($idAdserver, $category) {
       
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->newCategory($category);
    }

    /*     * *
     * @param integer $idAdserver, Section $section
     */

    protected static function newSection($idAdserver, $section) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->newSection($section);
    }

    /*     * *
     * @param integer $idAdserver, Placement $placement
     */

    protected static function newPlacement($idAdserver, $placement) {

        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->newPlacement($placement);
    }

    protected static function newVideo($idAdserver, $bridVideo) {
        $bridApi = new BridApi(array('auth_token' => 'eebdc5644e88590a7402d49e1af26617c86e4c3a'));

        $pathinfo = pathinfo($bridVideo->getUrl());

        $video_url = $bridVideo->getUrl();

        if ($bridVideo->placement->site->brid) {
            $brid_site_id = $bridVideo->placement->site->brid->getBridId();
        } else {
            //Crear Sitio en Brid 
            $bridSite = new BridSite();
            $bridSite->setSite($bridVideo->placement->site->getId());

            $res_add_site = self::newBridSite($bridSite);
            
            if ($res_add_site->status == 'success') {
                $bridSite->setBridId($res_add_site->partnerId);
                $bridSite->save();

                $brid_site_id = $res_add_site->partnerId;
            }else{
                return false;
            }
        }
        
        $youtubeData = array('url' => $video_url, 'return' => 'php');

        if (!isset($pathinfo['extension'])) {

            $_data = $bridApi->checkUrl($youtubeData, true);
            //$_data->mp4 = $video_url; //required
            $_data->channel_id = 52; //required
            $_data->partner_id = 2688; //required
            $_data->monetize = true;

            $res_add_video = json_decode($bridApi->addVideo((array) $_data));
        } else {

            $_data['mp4'] = $video_url; //required
            $_data['channel_id'] = 52; //required
            $_data['partner_id'] = $brid_site_id; //site_id
            $_data['name'] = $pathinfo['filename'] . ' - Adtomatik'; //name
            $_data['monetize'] = true;
            
            $res_add_video = json_decode($bridApi->addVideo($_data));
        }

        if ($res_add_video->status == 'success') {

            //Agregar TAG de DFP
            $size = $bridVideo->placement->size->getWidth() . 'x' . $bridVideo->placement->size->getHeight();
            $key = $bridVideo->placement->getKey();
            $site_name = $bridVideo->placement->site->getName();
            $adserver = Adserver::find(Session::get('adserver.id'));
            $adserverUser = $adserver->getUsername();

            $tag_dfp = "http://pubads.g.doubleclick.net/gampad/ads?sz=$size&iu=/$adserverUser/$key&ciu_szs&impl=s&gdfp_req=1&env=vp&output=xml_vast2&unviewed_position_start=1&url=$site_name&description_url=$site_name&correlator=[timestamp]";

            $_data = array(
                'partner_id' => $brid_site_id,
                'id' => $res_add_video->videoId,
                'Ad' => array(
                    0 => array(
                        'adType' => 0,
                        'adTagUrl' => $tag_dfp,
                    )
                )
            );
            
            $bridApi->editVideo($_data);
            
            //Crear LineItem en DFP
            
            
            return $res_add_video;
        }
        return false;
    }

    protected static function newBridSite(BridSite $bridSite) {

        $bridApi = new BridApi(array('auth_token' => 'eebdc5644e88590a7402d49e1af26617c86e4c3a'));
        return json_decode($bridApi->addPartner(array('domain'=> $bridSite->site->getName())));
    }

    protected static function deletePlacement($idAdserver, $placement) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->deletePlacement($placement);
    }

    protected static function getInventoryReport($idAdserver) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->getInventoryReport();
    }

    /*     * *
     * @param integer $idAdserver, Publisher $publisher
     */

    protected static function createMediaBuyer($idAdserver, $administrator) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->createMediaBuyer($administrator);
    }

    protected static function assignMediaBuyer($idAdserver, $publisher) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->assignMediaBuyer($publisher);
    }

    public static function getAdserverPlacementName($idAdserver, $placement) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->getAdserverPlacementName($placement);
    }

    /*     * *
     * @param integer $idAdserver
     */

    public static function validateToken($idAdserver) {
        
        $adserver = Adserver::find($idAdserver);
        if ((null !== ($adserver->getToken())) && ('no_apply' !== ($adserver->getToken()))) {
            $spentMinutes = $adserver->minutesOfLastSetToken();
            if ($spentMinutes->minutes . T_INT_CAST >= $adserver->getMinutesToExpireToken() . T_INT_CAST) {
                $adserver->setToken(self::refreshToken($idAdserver));
                $adserver->setTimeStampOfToken();
                $adserver->save();
            } else {
                $token = self::getActualToken($idAdserver);
                if ($token['token'] == '') {
                    $adserver->setToken(self::refreshToken($idAdserver));
                    $adserver->setTimeStampOfToken();
                    $adserver->save();
                } else {
                    if (!($token['token'] === ($adserver->getToken()))) {
                        $adserver->setToken($token['token']);

                        $adserver->setTimeStampOfTokenFixed($token['creation_time']);
                        $adserver->forceSave();

                        self::validateToken($idAdserver);
                    }
                }
            }
        } elseif ('no_apply' !== ($adserver->getToken())) {
            $adserver->setToken(self::refreshToken($idAdserver));
            $adserver->setTimeStampOfToken();
            $adserver->save();
        }
    }

    /*
     * Obtiene un reporte desde la base de datos para el adserver especificado
     * @param string Tipo de reporte (country, placement, month, day)
     * @param string Intervalo (month_to_date, 2014-05-18-to-2014-06-08)
     */

    public static function getReport($type, $interval, $idAdserver = null) {
        if (!isset($idAdserver)) {
            $adserver = Adserver::getDefault();
            $idAdserver = $adserver->getId();
        }
        $interval_str = $interval;
        $data = ['publisher_id' => null, 'start_date' => null, 'end_date' => null, 'group_by' => null, 'columns' => null];
        $interval = getDatetimeByInterval($interval);

        $data['publisher_id'] = Session::get('publisher.id');
        $data['start_date'] = $interval['start_date'];
        $data['end_date'] = $interval['end_date'];

        switch ($type) {
            case 'site_name';
                $data['group_by'] = 'site_adserver_id';
                break;
            case 'site_placement';
                $data['group_by'] = 'site_and_placement';
                break;
            case 'placement';
                $data['group_by'] = 'placement_adserver_id';
                break;
            case 'country';
                $data['group_by'] = 'country_adserver_id';
                break;
            case 'country_size';
                $data['group_by'] = 'country_and_size';
                break;
            case 'format';
                $data['group_by'] = 'size_adserver_id';
                break;
            case 'day':
                $data['group_by'] = 'day';
                break;
            case 'month':
                $data['group_by'] = 'month';
                break;
            case 'home':
                $data['group_by'] = 'publisher_adserver_id';
                break;
            default:
                $data['group_by'] = 'day';
                break;
        }

        $data['columns'] = Config::get('columns.' . $data['group_by']);

        $data['group_by'] = Config::get('groupby.' . $data['group_by']);

        if ($interval['start_date'] === date('Y-m-d 00:00:00') && $interval['end_date'] === date('Y-m-d 23:59:59') && $interval_str != 'month_to_date') {
            self::validateToken($idAdserver);
            $api = self::getAdserverClassName($idAdserver);
            $api = new $api();

            $report = $api->getReport($data);
        } else {
            $report = Inventory::getReport($data, $idAdserver);
        }

        $data['columns'][] = 'imps';
        $data['columns'][] = 'clicks';
        $data['columns'][] = 'ctr';
        $data['columns'][] = 'cpm';
        $data['columns'][] = 'revenue';

        $data['date']['start'] = $data['start_date'];
        $data['date']['end'] = $data['end_date'];

        $report = array(
            'report' => $report,
            'columns' => $data['columns'],
            'date' => $data['date']
        );

        return $report;
    }

    /*
     * Obtiene obtiene el revenue de un publisher segun la fecha especificada
     * @param string Intervalo (month_to_date, 2014-05-18-to-2014-06-08)
     */

    public static function getRevenueByDate($interval, $idAdserver = null) {
        if (!isset($idAdserver)) {
            $adserver = Adserver::getDefault();
            $idAdserver = $adserver->getId();
        }
        $interval = getDatetimeByInterval($interval);

        $publisher = Publisher::find(Session::get('publisher.id'));
        $data['publisher_id'] = Session::get('publisher.id');
        $data['start_date'] = $interval['start_date'];
        $data['end_date'] = $interval['end_date'];

        $data['group_by'] = 'publisher_adserver_id';
        $data['columns'] = Config::get('groupby.' . $data['group_by']);

        $report = Inventory::getRevenueByDate($data, $idAdserver);

        if ($report) {
            $report = $report[0]->revenue;
        } else {
            $report = 0;
        }

        return $report;
    }

    /*
     * Obtiene obtiene el revenue de un publisher segun la fecha especificada
     * @param string Intervalo (month_to_date, 2014-05-18-to-2014-06-08)
     */

    public static function getDataGraph($interval, $group, $idAdserver = null) {
        if (!isset($idAdserver)) {
            $adserver = Adserver::getDefault();
            $idAdserver = $adserver->getId();
        }
        $interval = getDatetimeByInterval($interval);

        $data['publisher_id'] = Session::get('publisher.id');
        $data['start_date'] = $interval['start_date'];
        $data['end_date'] = $interval['end_date'];

        if ($group !== 'month' && $group !== 'day')
            $group = 'day';

        $data['group_by'] = $group;
        $data['columns'] = Config::get('groupby.day');

        $inventory = new Inventory($idAdserver);
        $reports = $inventory->getDataGraph($data);

        $response['categories'] = $response['data'] = '';
        $max = 0;

        if ($reports) {
            foreach ($reports as $report) {
                $response['categories'] .= '"' . $report->day . '",';
                $response['data'] .= $report->revenue . ',';
                $max++;
            }

            $response['categories'] = trim($response['categories'], ',');
            $response['data'] = trim($response['data'], ',');
            $response['max'] = $max - 1;
        } else {
            $response == NULL;
        }

        return $response;
    }

    /*
     * Obtiene obtiene el revenue de un publisher segun la fecha especificada
     * @param string Intervalo (month_to_date, 2014-05-18-to-2014-06-08)
     */

    public static function getDataGraphMap($interval, $idAdserver = null) {
        if (!isset($idAdserver)) {
            $adserver = Adserver::getDefault();
            $idAdserver = $adserver->getId();
        }
        $interval = getDatetimeByInterval($interval);

        $data['publisher_id'] = Session::get('publisher.id');
        $data['start_date'] = $interval['start_date'];
        $data['end_date'] = $interval['end_date'];

        $data['group_by'] = 'country_name';
        $data['columns'] = ['country_name', 'country_adserver_id'];

        $inventory = new Inventory($idAdserver);
        $reports = $inventory->getDataGraphMap($data);

        $response['data'] = '';

        if ($reports) {

            $max = 0;

            foreach ($reports as $report) {
                if ($report->revenue < 0)
                    $report->revenue = 0;

                if ($report->revenue > $max)
                    $max = $report->revenue;

                $report->country_name = Lang::get('countries.' . $report->country_id);

                $response['data'] .= '{"code": "' . $report->country_id . '", "value": ' . $report->revenue . ', "name": "' . $report->country_name . '"},';
            }

            $response['data'] = trim($response['data'], ',');
            $response['max'] = $max;
        } else {
            $response == NULL;
        }

        return $response;
    }

    /*     * *
     * @param integer $idAdserver
     */

    public static function refreshToken($idAdserver) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->refreshToken();
    }

    public static function getActualToken($idAdserver) {
        $api = self::getAdserverClassName($idAdserver);
        $api = new $api();
        return $api->getActualToken();
    }

}
