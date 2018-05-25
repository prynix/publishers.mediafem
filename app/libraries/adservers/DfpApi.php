<?php

class Credentials {

    public static $production = array(
        'oauth2info' => array('client_id' => "56018185674-7d8uc8mc9bjo1j1hb5lrpte9qptn6jj3.apps.googleusercontent.com",
            'client_secret' => "OeYlLmOFyIfp9KaxGaJhm20w",
            'refresh_token' => '1/DU2ZnIb3oKBpThisOt15Q3-_jTRdrESg8NJtyrPVFRk'),
        'code' => '25379366', 'name' => 'AdtomatikForAdmin'
    );
    public static $test = array(
        'oauth2info' => array('client_id' => "765956154715-uhil3e6tcpm4a8ourc2ij9da1g7hnbl9.apps.googleusercontent.com",
            'client_secret' => "4t_T1GV_D9ebvbB30soGfqrY",
            'refresh_token' => "1/qlvmbuR8lL-FNujk3yJqM88xOkxsQ7MOX6ElEH3u04QMEudVrK5jSpoR30zcRFq6"),
        'code' => '40590846', 'name' => 'adtomatik'
    );

    public static function get() {
        //return self::$test;
        return self::$production;
    }

}

class DfpApi {

    const VERSION = 'v201408';
    const SOAP_BASE = 'http://adserver.adtomatik.com/';

    //const SOAP_BASE = 'http://localhost:8002/';

    public $adserver;

    public function __construct() {
        $this->adserver = $this->getAdserver();
    }

    /*     * ****************** Publisher ************* */

    public function newPublisher(Publisher $publisher) {
        $data_publisher = new stdClass();
        $data_publisher->adunit = new stdClass();
        $data_publisher->adunit->name = 'ADT_Publisher_' . $publisher->getName() . '_' . $publisher->user->getId();
        $data_publisher->adunit->description = 'Adtomatik Publisher';
        $data_publisher->adunit->targetWindow = 'BLANK';

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setUri(self::SOAP_BASE . 'new_adunit');
        $request->setData(array('credentials' => Credentials::get(), 'data' => $data_publisher));
        $request->setDecodeResponse(TRUE);
        
        $call = new Caller();
        try {
            $res = $call->call($request);
            
            if ($res->error === FALSE) {
                return ['publisher_key' => $res->message->id];
            } else
                throw new Exception($res->message);
        } catch (Exception $ex) {
            $this->_manageException('New Publisher ' . $data_publisher->adunit->name . ': ' . $ex->getMessage());
        }
    }

    public function newLineItem($lineItem) {
        $data_lineItem = new stdClass();
        $data_lineItem->lineitem = new stdClass();
        $data_lineItem->lineitem->name = $lineItem['site_name'] . '_video';
        $data_lineItem->lineitem->order_id = $lineItem['order_id'];
        $data_lineItem->lineitem->adunit_id = $lineItem['adunit_id'];

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setUri(self::SOAP_BASE . 'new_lineitem');
        $request->setData(array('credentials' => Credentials::get(), 'data' => $data_lineItem));
        $request->setDecodeResponse(TRUE);
        $call = new Caller();
        try {
            $res = $call->call($request);

            dd($res);

            if ($res->error === FALSE) {
                return ['publisher_key' => $res->message->id];
            } else
                throw new Exception($res->message);
        } catch (Exception $ex) {
            $this->_manageException('New Publisher ' . $data_publisher->adunit->name . ': ' . $ex->getMessage());
        }
    }

    /*     * ****************** Site ****************** */

    public function newSite(Site $site) {
        $data_site = new stdClass();
        $data_site->adunit = new stdClass();
        $data_site->adunit->name = 'ADT_Site_' . $site->getName() . '_' . $site->getId() . '--' . $site->publisher->getAdserverKey($this->adserver->getId());
        $data_site->adunit->description = 'Adtomatik Site';
        $data_site->adunit->targetWindow = 'BLANK';
        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setUri(self::SOAP_BASE . 'new_adunit');
        $request->setData(array('credentials' => Credentials::get(), 'data' => $data_site));
        $request->setDecodeResponse(TRUE);
        $call = new Caller();
        
        try {
            
            $res = $call->call($request);
          
            if ($res->error === FALSE)
                return $res->message->id;
            else
                throw new Exception($res->message);
        } catch (Exception $ex) {
            $this->_manageException('New Site ' . $data_site->adunit->name . ': ' . $ex->getMessage());
        }
    }

    /*     * ****************** Placement ************* */

    public function newPlacement(Placement $placement) {
        $data_placement = new stdClass();
        $data_placement->adunit = new stdClass();
        $data_placement->adunit->name = 'ADT_Placement_' . $placement->getName() . '_' . $placement->site->getId() . '--' . $placement->site->getAdserverKey($this->adserver->getId());
        $data_placement->adunit->description = 'Adtomatik Placement';
        $data_placement->adunit->targetWindow = 'BLANK';

        $data_size = new stdClass();
        $data_size->size = new stdClass();
        if ($placement->size->getAdserverKey($this->adserver->getId()) == 'video' || $placement->size->getAdserverKey($this->adserver->getId()) == 'video-player')
            $data_size->size->environmentType = "VIDEO_PLAYER";
        else
            $data_size->size->environmentType = "BROWSER";

        if ($placement->size->getAdserverKey($this->adserver->getId()) == 'out-of-page') {
            $data_size->size->width = 1;
            $data_size->size->height = 1;
        } else {
            $data_size->size->width = $placement->size->getWidth();
            $data_size->size->height = $placement->size->getHeight();
        }

        $data_placement->adunit->size = $data_size->size;

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setUri(self::SOAP_BASE . 'new_adunit');
        $request->setData(array('credentials' => Credentials::get(), 'data' => $data_placement));
        $request->setDecodeResponse(TRUE);
        $call = new Caller();
        
        try {
            $res = $call->call($request);
            
            //var_dump($res);
            //echo "\n\n\n";
            //echo "error: ".$res->error ."\n";
            
            if ($res->error === FALSE) {
                //echo "1 \n";
                if ($placement->size->getAdserverKey($this->adserver->getId()) == 'video-player') {
                    //echo "2 \n";
                    //Si el sitio no tiene LineItem lo creo
                    if (!$placement->site->getLineItemId()) {
                        //echo "3 \n";
                        //dd("a");
                        //Crear LineItem
                        $data_lineitem = new stdClass();
                        $data_lineitem->lineitem = new stdClass();
                        $data_lineitem->lineitem->name = $placement->site->getName() . '_video_' . $placement->site->getId();
                        $data_lineitem->lineitem->order_id = 389380006;
                        $data_lineitem->lineitem->adunit_id = $res->message->id;

                        $request_li = new ApiRequest();
                        $request_li->setMethod('post');
                        $request_li->setUri(self::SOAP_BASE . 'new_lineitem');
                        $request_li->setData(array('credentials' => Credentials::get(), 'data' => $data_lineitem));
                        $request_li->setDecodeResponse(TRUE);
                        $call = new Caller();
                        //dd($request_li);
                        $res_li = $call->call($request_li);
                        
                        //dd($res_li);
                        
                        Site::updateLineItemId($placement->site->getId(), $res_li->message->id);
                    } else {
                        //echo "4 \n";
                        //dd("a");
                        //Si el sitio ya tiene LineItem lo actualizo con un nuevo adunit
                        $placements = Placement::getPlacementsBySiteAndSize($placement->site->getId(), 11);

                        $arra_adunits = null;
                        $arra_adunits[] = $res->message->id;
                        foreach ($placements as $plc) {
                            $arra_adunits[] = $plc->plc_adserver_key;
                        }

                        //Crear LineItem
                        $data_lineitem = new stdClass();
                        $data_lineitem->lineitem = new stdClass();
                        $data_lineitem->lineitem->lineitem_id = $placement->site->getLineItemId();
                        $data_lineitem->lineitem->adunits = $arra_adunits;

                        $request_li = new ApiRequest();
                        $request_li->setMethod('post');
                        $request_li->setUri(self::SOAP_BASE . 'update_lineitem');
                        $request_li->setData(array('credentials' => Credentials::get(), 'data' => $data_lineitem));
                        $request_li->setDecodeResponse(TRUE);
                        $call = new Caller();
                        //dd($request_li);
                        $res_li = $call->call($request_li);
                        
                        //dd($res_li);
                    }
                }
                //echo "5 \n";
                return $res->message;
            } else {
                //echo "6 \n";
                throw new Exception($res->message);
            }
        } catch (Exception $ex) {
            $this->_manageException('New Placement ' . $data_placement->adunit->name . ': ' . $ex->getMessage());
        }
    }

    public function getAdserverPlacementName($placement) {
        return 'ADT_Placement_' . $placement->getName() . '_' . $placement->site->getId() . '--' . $placement->site->getAdserverKey($this->adserver->getId());
    }

    /*     * ****************** Categories ************ */

    public function categorizeSites($sites) {
        $listSiteIds = implode(', ', array_map(function ($value) {
                    return $value->sit_id;
                }, $sites));
        $categories = Category::getAll($this->adserver->getId());
        foreach ($categories as $categoryid) {
            $category = Category::find($categoryid->category_id);
            echo $category->getName() . "\n";
            $categoryKeyAndAdUnit = $category->getAdserverKey($this->adserver->getId());
            $categoryKeys = explode("&", $categoryKeyAndAdUnit[0]);

            $placementsKeys = Placement::getPlacementsKeysByCategory($categoryid->category_id);
            if (count($placementsKeys) > 0) {
                $listPlacementsKeys = implode(', ', array_map(function ($value) {
                            return $value->plc_adserver_key;
                        }, $placementsKeys));
                $listPlacementsKeys = $listPlacementsKeys . ', ' . $categoryKeys[1];
            } else {
                $listPlacementsKeys = $categoryKeys[1];
            }
            echo "Ad units: " . $listPlacementsKeys . "\n";
            $data = new stdClass();
            $data->placement = new stdClass();
            $data->placement->id = $categoryKeys[0];
            $data->placement->adunits = $listPlacementsKeys;

            $request = new ApiRequest();
            $request->setMethod('post');
            $request->setUri(self::SOAP_BASE . 'categorize_sites');
            $request->setData(array('credentials' => Credentials::get(), 'data' => $data));
            $request->setDecodeResponse(TRUE);
            $call = new Caller();
            try {
                $res = $call->call($request);
                if ($res->error === TRUE)
                    throw new Exception($res->message);
            } catch (Exception $ex) {
                $this->_manageException('Categorize Sites: ' . $ex->getMessage());
            }
            sleep(2);
        }
        Site::updateSitesSetCategorizedTrue(explode(", ", $listSiteIds));
    }

    public function newCategory(Category $category) {
        
        $adunitId = NULL;
        $data_adunit = new stdClass();
        $data_adunit->adunit = new stdClass();
        $data_adunit->adunit->name = 'Category ' . $category->getName();
        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setUri(self::SOAP_BASE . 'new_adunit');
        $request->setData(array('credentials' => Credentials::get(), 'data' => $data_adunit));
        $request->setDecodeResponse(TRUE);
        $call = new Caller();
        try {
            $res = $call->call($request);

            if ($res->error === FALSE)
                $adunitId = $res->message;
            else
                throw new Exception($res->message);
        } catch (Exception $ex) {
            $this->_manageException('New Category (AdUnit) ' . $data_adunit->adunit->name . ': ' . $ex->getMessage());
        }
        
        //return $adunitId;
        //$adunitId = 44916766;
        if ($adunitId) {
            $data_category = new stdClass();
            $data_category->placement = new stdClass();
            $data_category->placement->name = $category->getName();
            $data_category->placement->defaultAdunit = $adunitId;
            $request = new ApiRequest();
            $request->setMethod('post');
            $request->setUri(self::SOAP_BASE . 'new_placement');
            $request->setData(array('credentials' => Credentials::get(), 'data' => $data_category));
            $request->setDecodeResponse(TRUE);
            $call = new Caller();

            var_dump($request);

            try {
                $res = $call->call($request);
                
                echo "\n\nResultado:\n\n";
                dd($res);

                if ($res->error === FALSE)
                    return $res->message . '&' . $adunitId;
                else
                    throw new Exception($res->message);
            } catch (Exception $ex) {
                $this->_manageException('New Category (Placement)' . $data_category->placement->name . ': ' . $ex->getMessage());
            }
        } else {
            $this->_manageException('New Category ' . $category->getName() . ': ' . "El ad unit no se creo correctamente");
        }
    }

    /*     * ****************** Media Buyer *********** */

    public function createMediaBuyer($param = NULL) {
        return "no_apply";
    }

    public function assignMediaBuyer(Publisher $publisher = NULL) {
        return TRUE;
    }

    /*     * ****************** Payment Rules ********* */

    public function assignPaymentRule($param = NULL) {
        return TRUE;
    }

    /*     * ****************** Orders **************** */

    public function getOrders() {
        $data_order = new stdClass();
        $data_order->filters = new stdClass();
        $data_order->filters->status = ['UNKNOWN'];
        //$data_order->filters->id = [389380006];

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setUri(self::SOAP_BASE . 'get_orders');
        $request->setData(array('credentials' => Credentials::get(), 'data' => NULL));
        //$request->setData(array('credentials' => Credentials::get(), 'data' => $data_order));
        $request->setDecodeResponse(TRUE);
        $call = new Caller();
        try {
            $res = $call->call($request);
            return $res->message;
        } catch (Exception $ex) {
            $this->_manageException('Get Orders ' . ': ' . $ex->getMessage());
        }
    }

    public function excludeAdunitsFromLineitem($idLineitem = NULL, $excludeAdUnits = NULL) {
        $data_lineitem = new stdClass();
        $data_lineitem->lineitem = new stdClass();
        $data_lineitem->lineitem->adunits = $excludeAdUnits;
        $data_lineitem->lineitem->id = $idLineitem;

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setUri(self::SOAP_BASE . 'target_adunits_lineitem');
        $request->setData(array('credentials' => Credentials::get(), 'data' => $data_lineitem));
        $request->setDecodeResponse(TRUE);
        $call = new Caller();
        try {
            $res = $call->call($request);
            if ($res->error === FALSE) {
                return TRUE;
            } else {
                echo $res->message . "\n";
                $this->_manageException('Exclude from Line Item ' . ': ' . $res->message);
                return FALSE;
            }
        } catch (Exception $ex) {
            $this->_manageException('Exclude from Line Item ' . ': ' . $ex->getMessage());
        }
    }

    /*     * ****************** Reports *************** */

    public function getReport($data = NULL) {
        return NULL;
    }

    public function getOrderReport($lineitem) {
        $startHour = 0;
        $endHour = 0;

        $filesPath = "";
        $reportData = new stdClass();
        $reportData->report = new stdClass();
        $reportData->report->filter = 'line_item_id = ' . $lineitem;

        $reportData->report->order = true;

        $dateNow = date("Y-m-d H:i:s");
        //$dateNow = date("Y-m-d 00:20:00");
        $date = date("Y-m-d 00:00:00");
        echo $date . "\n";
        echo $dateNow . "\n";
        $passedHours = floor((strtotime($dateNow) - strtotime($date)) / 3600);
        echo "(" . $passedHours . ") horas transcurridas del dia actual.\n";

        if (6 <= $passedHours) {
            $reportData->report->date = "TODAY";
            $startHour = $passedHours - 6;
            $endHour = $passedHours - 1;
        } else {
            $reportData->report->date = "YESTERDAY";
            $startHour = 18;
            $endHour = 23;
        }
        echo 'Datos a analizar del dia: ' . $reportData->report->date . "\n";

        $reportData->report->groupby = ['DATE', 'HOUR', 'AD_UNIT_ID', 'AD_UNIT_NAME'];
        $reportData->report->columns = ['TOTAL_LINE_ITEM_LEVEL_IMPRESSIONS', 'TOTAL_LINE_ITEM_LEVEL_CLICKS', 'TOTAL_LINE_ITEM_LEVEL_CTR'];
        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setUri(self::SOAP_BASE . 'get_report');
        $request->setData(array('credentials' => Credentials::get(), 'data' => $reportData));
        $request->setDecodeResponse(TRUE);
        $call = new Caller();
        try {
            $res = $call->call($request);
            if ($res->error === FALSE) {
                $filesPath = $res->message;
            } else
                throw new Exception($res->message);
        } catch (Exception $ex) {
            $this->_manageException('Get Order Report: ' . $ex->getMessage());
        }
        try {
            return ControlCtr::processOrderReport($this->unpackAndProcessData($filesPath), $startHour, $endHour);
        } catch (Exception $ex) {
            $this->_manageException('Process Report: ' . $ex->getMessage());
            return [];
        }
    }

    public function getInventoryReport() {
        $filesPath = array();
        $allAdunits = $this->getAdUnits();
        $groupAdunits = array_chunk($allAdunits, 1000);

	

        foreach ($groupAdunits as $adunits) {
            $listPlacementsKeys = implode(', ', array_map(function ($value) {
                        return $value->plc_adserver_key;
                    }, $adunits));
            $reportData = new stdClass();
            $reportData->report = new stdClass();

		
            $reportData->report->filter = 'AD_UNIT_ID in (' . $listPlacementsKeys . ')';

		
		
            $reportData->report->date = 'YESTERDAY';
            //$reportData->report->date = 'CUSTOM_DATE';
            //$reportData->report->startDate = '13-12-2016';
            //$reportData->report->endDate = '14-12-2016';
	
            $reportData->report->groupby = $this->getGroup(['placement_adserver_id', 'day', 'placement_name', 'country_name_string', 'country_name']);
            $reportData->report->columns = $this->getColumns(['impressions', 'clicks', 'revenue_adserver', 'revenue_adexchange']);

            $request = new ApiRequest();
            $request->setMethod('post');
            $request->setUri(self::SOAP_BASE . 'get_report');
            $request->setData(array('credentials' => Credentials::get(), 'data' => $reportData));
            $request->setDecodeResponse(TRUE);
            $call = new Caller();
            try {
		
                $res = $call->call($request);
                
                $intento_reporte = 0;
                while(is_null($res)){
                    $intento_reporte++;
                    echo "Intento: ".$intento_reporte."\n";
                    $res = $call->call($request);
                }
                echo "RES-1\n";
                var_dump($res);
                if($res){
                    if ($res->error == FALSE) {
                        echo "Message: ".$res->message."\n\n";
                        if (!strlen(trim($res->message))) {
                            $intentos = 0;
                            while (!strlen(trim($res->message))) {
                                $intentos++;
                                echo "Intento " . $intentos . "\n";
                                $res = $call->call($request);
                            }
                        }
                        $filesPath[] = $res->message;
                        echo "FilesPath:\n";
                        var_dump($filesPath);
                    } else {
                        echo "Message2: ".$res->message."\n\n";
                        throw new Exception($res->message);
                    }
                } else {
                    echo "Else:\n\n";
                    var_dump($res);
                }
            } catch (Exception $ex) {
                echo $ex->getTraceAsString();
                $this->_manageException('Get Order Report: ' . $ex->getMessage());
            }
        }
        //die();
        try {
            return $this->fillInventory($filesPath);
        } catch (Exception $ex) {
            $this->_manageException('Process Order Report: ' . $ex->getMessage());
        }
    }

    public function getMediaBuyerReport(Administrator $admin, $range = 'LAST_MONTH') {
        //foreach mediabuyers
        $filesPath = array();
        $allAdunits = [];
        $listPublishersId = array();
        $publishers = Publisher::getByAdserverAndMediaBuyer(3, $admin->getId());
        echo "\tPublishers asignados: " . count($publishers) . "\n";
        if (count($publishers) < 1) {
            return true;
        }
        //Get Publishers IDs + AdUnits
        foreach ($publishers as $publisher) {
            $listPublishersId[] = $publisher->getId();
            $adunits = self::getAdUnits($publisher);
            $allAdunits = array_merge($allAdunits, $adunits);
        }
        echo "\t(" . count($allAdunits) . " Ad Units)\n";

        $groupAdunits = array_chunk($allAdunits, 1000);

        foreach ($groupAdunits as $adunits) {
            $listPlacementsKeys = implode(', ', array_map(function ($value) {
                        return $value->plc_adserver_key;
                    }, $adunits));

            echo "\tCount: " . count($adunits) . " (" . $listPlacementsKeys . ")\n";
            $reportData = new stdClass();
            $reportData->report = new stdClass();
            $reportData->report->filter = 'AD_UNIT_ID in (' . $listPlacementsKeys . ')';
            if ($range == 'LAST_MONTH') {
                $reportData->report->date = 'LAST_MONTH';
                $range = date('Y-m-d', strtotime("first day of last month"));
            } else {
                $reportData->report->date = 'CUSTOM_DATE';
                $reportData->report->startDate = date('01-m-Y', strtotime($range));
                $reportData->report->endDate = date('t-m-Y', strtotime($range));
                echo "\tDFP Fechas " . date('01-m-Y', strtotime($range)) . ' - ' . date('t-m-Y', strtotime($range));
            }
            $reportData->report->groupby = $this->getGroup(['placement_adserver_id']);
            $reportData->report->columns = $this->getColumns(['impressions', 'revenue_adserver', 'revenue_adexchange']);

            $request = new ApiRequest();
            $request->setMethod('post');
            $request->setUri(self::SOAP_BASE . 'get_report');
            $request->setData(array('credentials' => Credentials::get(), 'data' => $reportData));
            $request->setDecodeResponse(TRUE);
            $call = new Caller();
            try {
                $res = $call->call($request);
                if ($res) {
                    if ($res->error == FALSE) {
                        $filesPath[] = $res->message;
                    } else {
                        echo $res->message;
                        throw new Exception($res->message);
                    }
                } else {
                    echo 'Error: ' . "\n";
                    var_dump($res);
                    return FALSE;
                }
            } catch (Exception $ex) {
                echo $ex->getTraceAsString();
                $this->_manageException('Get Order Report: ' . $ex->getMessage());
                return FALSE;
            }
        }
        try {
            if ($this->fillMediaBuyerCommissions($filesPath, $admin, $listPublishersId, $range) > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $ex) {
            $this->_manageException('Process Order Report: ' . $ex->getMessage());
            echo $ex->getMessage() . "\n";
            return false;
        }
    }

    public function getPublishersOptimization($param = NULL) {
        $filesPath = array();
        $allAdunits = $this->getAdUnits();
        $groupAdunits = array_chunk($allAdunits, 1000);
        foreach ($groupAdunits as $adunits) {
            $listPlacementsKeys = implode(', ', array_map(function ($value) {
                        return $value->plc_adserver_key;
                    }, $adunits));
            $reportData = new stdClass();
            $reportData->report = new stdClass();
            $reportData->report->filter = 'AD_UNIT_ID in (' . $listPlacementsKeys . ')';
            $reportData->report->date = 'YESTERDAY';
            //$reportData->report->date = 'CUSTOM_DATE';
            //$reportData->report->startDate = '03-11-2015';
            //$reportData->report->endDate = '03-11-2015';
            $reportData->report->groupby = $this->getGroup(['placement_adserver_id', 'day', 'placement_name', 'country_name_string', 'country_name']);
            $reportData->report->columns = $this->getColumns(['adserver_impressions', 'exchange_impressions', 'unfilled_impressions', 'revenue_adserver', 'revenue_adexchange']);

            $request = new ApiRequest();
            $request->setMethod('post');
            $request->setUri(self::SOAP_BASE . 'get_report');
            $request->setData(array('credentials' => Credentials::get(), 'data' => $reportData));
            $request->setDecodeResponse(TRUE);
            $call = new Caller();
            try {
                $res = $call->call($request);
                if ($res) {
                    if ($res->error == FALSE) {
                        $filesPath[] = $res->message;
                        var_dump($filesPath);
                    } else {
                        echo $res->message;
                        throw new Exception($res->message);
                    }
                } else {
                    var_dump($res);
                }
            } catch (Exception $ex) {
                echo $ex->getTraceAsString();
                $this->_manageException('Get Inventory Report: ' . $ex->getMessage());
            }
        }
        try {
            return $this->fillOptimization($filesPath);
        } catch (Exception $ex) {
            $this->_manageException('Fill Inventory Report: ' . $ex->getMessage());
        }
    }

    /*     * ******************************************** */
    /*     * ****************** Private Functions ******* */
    /*     * ******************************************** */

    private function getAdUnits($publisher = NULL) {
        if (!$publisher) {
            //All Placements
            return Placement::getByAdserverToOptimize($this->adserver->getId());
        } else {
            //Publisher adunits
            return $publisher->getAllPlacements();
        }
    }

    private function deleteTempFiles($filePath) {
        unlink($filePath);
        unlink(str_replace(".txt.gz", "", $filePath));
    }

    private function getNumber($value) {
        $value = str_replace('"', '', $value);
        $value = str_replace(',', '', $value);
        $value = str_replace('$', '', $value);
        $value = str_replace('?', '', $value);
        return $value;
    }

    private function getColumns($setColumns) {
        $adserverColumns = array(
            'impressions' => 'TOTAL_INVENTORY_LEVEL_IMPRESSIONS',
            'adserver_impressions' => 'AD_SERVER_IMPRESSIONS',
            'exchange_impressions' => 'AD_EXCHANGE_LINE_ITEM_LEVEL_IMPRESSIONS',
            'unfilled_impressions' => 'TOTAL_INVENTORY_LEVEL_UNFILLED_IMPRESSIONS',
            'clicks' => 'TOTAL_INVENTORY_LEVEL_CLICKS',
            'revenue_adserver' => 'AD_SERVER_CPM_AND_CPC_REVENUE',
            'revenue_adexchange' => 'AD_EXCHANGE_LINE_ITEM_LEVEL_REVENUE'
        );
        foreach ($setColumns as $column) {
            if (isset($adserverColumns[$column]))
                $columns[] = $adserverColumns[$column];
        }
        return $columns;
    }

    private function getGroup($data) {
        $groups = array();
        $adserverGroups = array(
            'placement_adserver_id' => 'AD_UNIT_ID',
            'day' => 'DATE',
            'placement_name' => 'AD_UNIT_NAME',
            'country_name_string' => 'COUNTRY_NAME',
            'country_name' => 'COUNTRY_CRITERIA_ID',
            'country_adserver_id' => 'COUNTRY_CRITERIA_ID',
            'month' => 'MONTH_AND_YEAR',
        );
        if ((in_array('site_adserver_id', $data) || in_array('publisher_adserver_id', $data) || in_array('size_adserver_id', $data)) && !in_array('placement_adserver_id', $data)) {
            $groups[] = 'AD_UNIT_ID';
        }
        foreach ($data as $group) {
            if (isset($adserverGroups[$group]))
                $groups[] = $adserverGroups[$group];
        }
        return $groups;
    }

    private function getRevenuePlacementCountry($revenue_adserver, $revenue_adexchage, $placement_id, $country_id) {
        $placement = Placement::getByKey($placement_id);
        $publisher_share = $placement->site->publisher->getRevenue();
        $country = Country::getCountryId($country_id, 3);
        $revenue_share_adserver = 0;
        $revenue_share_exchange = 0;
        /*
          $payment_rule_adserver = PaymentRule::getPaymentRule($placement->getId(), $country, 'adserver');
          if ($payment_rule_adserver) {
          $revenue_share_adserver = $payment_rule_adserver->share;
          } else {
          if ($publisher_share != 0)
          $revenue_share_adserver = $publisher_share;
          else
          $revenue_share_adserver = Constant::value('revenue_adserver_dfp');
          }
         */
        $revenue_share_adserver = Constant::value('revenue_adserver_dfp');

        $payment_rule_exchange = PaymentRule::getPaymentRule($placement->getId(), $country, 'exchange');
        if ($payment_rule_exchange) {
            $revenue_share_exchange = $payment_rule_exchange->share;
        } else {
            if ($publisher_share != 0)
                $revenue_share_exchange = $publisher_share;
            else
                $revenue_share_exchange = Constant::value('revenue_exchange_dfp');
        }
        return ($revenue_adserver / 100 * $revenue_share_adserver) + ($revenue_adexchage / 100 * $revenue_share_exchange);
    }

    private function unpackAndProcessData($filesPath) {
        $paths = array();
        $report = array();
        if (!is_array($filesPath)) {
            $paths[] = $filesPath;
        } else {
            $paths = $filesPath;
        }
        foreach ($paths as $filePath) {
            $ruta = explode('/', $filePath);
            $rows = gzfile('http://adserver.adtomatik.com/tempfiles/' . $ruta[count($ruta) - 1]);
            echo "\tRuta del reporte DFP: " . 'http://adserver.adtomatik.com/tempfiles/' . $ruta[count($ruta) - 1] . "\n";
            //Quita primer elemento
            array_shift($rows);
            //Quita ultimo elemento
            array_pop($rows);
            try {
                if (sizeof($rows) > 0) {
                    echo "\tFilas en el reporte " . sizeof($rows) . "\n";
                    foreach ($rows as $row) {
                        try {
                            $columns = explode("\t", $row);
                            if (trim($columns[0]) == '' || trim($columns[0]) == 'Total') {
                                continue;
                            }
                            $report[] = $columns;
                        } catch (Exception $exc) {
                            echo $exc->getTraceAsString() . "\n";
                        }
                    }
                } else {
                    echo "\nFilas con datos relevantes: " . sizeof($rows) . "\n";
                }
            } catch (Exception $exc) {
                echo $exc->getTraceAsString() . "\n";
            }
            //$this->deleteTempFiles($filePath);
        }
        return $report;
    }

    private function fillInventory($filesPath) {
        $count = 0;
        $report = $this->unpackAndProcessData($filesPath);
        foreach ($report as $columns) {
            try {
                $inventory = new Inventory($this->adserver->getId());
                $inventory->setAdserver($this->adserver->getId());
                $inventory->setDay($columns[1]);
                $inventory->setPlacementName($columns[2]);
                $inventory->setPlacementAdserverId($columns[0]);
                $inventory->setCountryName($columns[3]);
                $inventory->setCountryAdserverId($columns[4]);
                $inventory->setImps($this->getNumber($columns[5]));
                $inventory->setClicks($this->getNumber($columns[6]));
                $inventory->setRevenue($this->getRevenuePlacementCountry($this->getNumber($columns[7]), $this->getNumber($columns[8]), $columns[0], $columns[4]));
                $inventory->printRow();
                $inventory->save();
                $count++;
            } catch (Exception $exce) {
                echo $exce->getTraceAsString() . "\n";
            }
        }
        return $count;
    }

    private function fillMediaBuyerCommissions($filesPath, $mediabuyer, $publishers_ids, $range) {
        $count = 0;
        $imps = 0;
        $rev = 0;
        $report = $this->unpackAndProcessData($filesPath);
        $adunits = array();
        foreach ($report as $columns) {
            try {
                $adunits[] = $columns[0];
                $imps = $imps + $this->getNumber($columns[1]);
                $rev = $rev + $this->getNumber($columns[2]) + $this->getNumber($columns[3]);
                $count++;
            } catch (Exception $exce) {
                echo $exce->getTraceAsString() . "\n";
            }
        }
        echo "AdUnits en el reporte: " . count($adunits) . "\n";
        if ($count < 1) {
            return FALSE;
        }
        //Calculate Cost DFP
        $dfpCost = DB::table('inventory_dfp')
                ->select(DB::raw("SUM(inventory_dfp.revenue) as 'cost', SUM(inventory_dfp.imps) as 'imps'"))
                ->where('inventory_dfp.day', '>=', date('Y-m-01', strtotime($range)))
                ->where('inventory_dfp.day', '<=', date('Y-m-t', strtotime($range)))
                ->whereIn('inventory_dfp.publisher_id', $publishers_ids)
                ->get();
        $mbc = new MediaBuyerCommission();
        $mbc->setAdserver($this->adserver->getId());
        $mbc->setAdministrator($mediabuyer->getId());
        $mbc->setCost($this->getNumber($dfpCost[0]->cost));
        //$mbc->setCost($rev / 2);
        $mbc->setPeriod(date('Y-m-01', strtotime($range)));
        $mbc->setImps($imps);
        $mbc->setRevenue($rev);
        $mbc->setProfit($rev - $this->getNumber($dfpCost[0]->cost));
        //$mbc->setProfit($rev - ($rev / 2));
        $mbc->setCommission($mediabuyer->getRevenueShare('mediabuyer'));
        $mbc->save();
        echo "\t\tAdunits leidos: " . count($adunits) . "\n";
        echo "\t\tRevenue: $" . $mbc->getRevenue() . "\n";
        echo "\t\tProfit: $" . $mbc->getProfit() . "\n";
        echo "\t\tCosto: $" . $mbc->getCost() . "\n";
        echo "\t\tComision: $" . $mbc->getCommission() . "\n";
        echo "\t\tImps DB: " . $this->getNumber($dfpCost[0]->imps) . "\n";
        echo "\t\tImps DFP: " . $imps . "\n";
        echo "\t\tDiferencia de Imps: " . $imps - $this->getNumber($dfpCost[0]->imps) . "\n";
        return $count;
    }

    private function fillOptimization($filesPath) {
        $count = 0;
        $report = $this->unpackAndProcessData($filesPath);
        foreach ($report as $columns) {
            try {
                $minimum_imps_to_optimize = Constant::value('minimum_imps_to_optimize');
                $adserver_imps = $this->getNumber($columns[5]) + $this->getNumber($columns[7]);
                $exchange_imps = $this->getNumber($columns[6]);
                if (($adserver_imps >= $minimum_imps_to_optimize) || ($exchange_imps >= $minimum_imps_to_optimize)) {
                    $po = new PublisherOptimizationDfp();
                    $po->setPlacement($columns[0]);
                    $po->setSite();
                    $po->setPublisher();
                    $po->setPlacementName($columns[2]);
                    $po->setCountry($columns[4], $this->adserver->getId());
                    $po->setImpsAdserver($this->getNumber($columns[5]));
                    $po->setImpsExchange($exchange_imps);
                    $po->setUnfilledImps($this->getNumber($columns[7]));
                    $po->setRevenueAdserver($this->getNumber($columns[8]));
                    $po->setRevenueExchange($this->getNumber($columns[9]));
                    $po->setPublisherName($po->publisher->getName());
                    $po->setSiteName($po->site->getName());
                    $po->setAdserver($this->adserver);
                    try {
                        $po->save();
                        $count++;
                    } catch (Exception $ex) {
                        echo "\n Error: " . $ex->getMessage() . "\n";
                    }
                }
            } catch (Exception $exce) {
                echo $exce->getTraceAsString() . "\n";
            }
        }
        var_dump("Agregados " . $count . " registros");
        return sizeof($report);
    }

    /*     * *********************
     * ********* Others
     * ********************* */

    private function getAdserver() {
        return Adserver::where('adv_class_name', get_class($this))->first();
    }

    private function _manageException($ex) {
        // open log file
        $filename = public_path() . "/logs/dfp.log";
        $fh = fopen($filename, "a") or die();
        fwrite($fh, date("d-m-Y, H:i") . " - $ex\n") or die();
        fclose($fh);
    }

}
