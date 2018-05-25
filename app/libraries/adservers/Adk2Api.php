<?php

class Adk2Api {

    const SOAP_BASE = 'http://adtomatik.adk2.com/';

    private $token;
    private $adserver;
    private $tokenUrl;

    function __construct() {
        $this->adserver = $this->getAdserver();
        $this->token = $this->adserver->getToken();
        $this->tokenUrl = '?auth=' . $this->token;
    }

//OK
    public function refreshToken() {
        return $this->token;
    }

//OK
    private function authenticate() {
        $data_auth = new stdClass();
        $data_auth->email = $this->adserver->getUsername();
        $data_auth->password = $this->adserver->getPassword();

        $request_auth = new ApiRequest();
        $request_auth->setMethod('post');
        $request_auth->setUri(self::SOAP_BASE . 'auth');
        $request_auth->setData($data_auth);

        $call = new Caller();

        try {
            $res = $call->call($request_auth);

            if ($res) {
                if ($res->email) {
                    if ($res->email == $this->adserver->getUsername())
                        return TRUE;
                }
            }
            return FALSE;
        } catch (Exception $ex) {
            $this->_manageException('Authenticate: ' . $ex->getMessage());
        }
    }

//OK
    public function getActualToken() {
        $token = ['token' => $this->token, 'creation_time' => $this->adserver->getTokenSet()];
        return $token;
    }

    public function createMediaBuyer($admin) {
        return $admin->user->getEmail();
    }
//OK
    public function newPublisher(Publisher $publisher) {
        $data_publisher = new stdClass();
        $data_publisher->publisher = new stdClass();
        $data_publisher->publisher->accountName = $publisher->getName() . '(ADT)' . $publisher->user->getEmail() . '_' . $publisher->user->getId();
        $data_publisher->publisher->manager = 'media@mediafem.com';
        $data_publisher->publisher->salesManager = 'media@mediafem.com';
        $data_publisher->publisher->status = 'Approved';
        $data_publisher->publisher->share = floatval($this->getRevenueShareDecimal(Constant::value('default_revenue_share_adk2')));

        $categories = array();
        $categories[] = 1018;
        $placements = array();
        $placement = new stdClass();
        $placement->name = 'Default';
        $placement->active = FALSE;
        $placement->properties = new stdClass();
        $placement->properties->category = $categories;

        $placements[] = $placement;

        $data_publisher->publisher->placements = $placements;

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'exchange/publishers' . $this->tokenUrl);
        $request->setData($data_publisher->publisher);

        $call = new Caller();
        try {
            $res = $call->call($request);
            if (isset($res->id)) {
                return array(
                    'publisher_key' => $res->id
                );
            } else {
                throw new Exception($res->message);
            }
        } catch (Exception $ex) {
            $this->_manageException('New Publisher ' . $publisher->getName() . ': ' . $ex->getMessage());
        }
    }

//OK
    public function createPaymentRule($param) {
        return TRUE;
    }

//OK
    public function assignPaymentRule($param) {
        return TRUE;
    }

//OK
    public function getPaymentRuleId($key_publisher) {
        return 0;
    }

//OK
    public function newDefaultCreative(Size $size, $publisherKey) {
        return TRUE;
    }

//OK
    public function assignMediaBuyer(Publisher $publisher) {
        $publisherAdk2 = $this->getPublisher($publisher->getAdserverKey($this->adserver->getId()));
        unset($publisherAdk2->audit);
        unset($publisherAdk2->created);
        unset($publisherAdk2->createdBy);
        unset($publisherAdk2->modifiedBy);
        unset($publisherAdk2->modified);
        unset($publisherAdk2->payee);
        $publisherAdk2->manager = $publisher->mediaBuyer->getAdserverKey($this->adserver->getId());
        $publisherAdk2->salesManager = $publisher->mediaBuyer->getAdserverKey($this->adserver->getId());

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'exchange/publishers/' . $publisher->getAdserverKey($this->adserver->getId()) . $this->tokenUrl);
        $request->setData($publisherAdk2);

        $call = new Caller();
        try {
            $call->call($request);
            return true;
        } catch (Exception $ex) {
            $this->_manageException('Assign media buyer to ' . $publisher->getName() . ': ' . $ex->getMessage());
            return false;
        }
    }

//OK
    public function newSite(Site $site) {

        $data_publisher = new stdClass();
        $data_publisher->publisher = new stdClass();

        $placements = array();
        $placement = new stdClass();
        $placement->name = $site->getName();
        $placement->active = TRUE;
        $placement->properties = new stdClass();
        $categories = Category::getAdserverDefaultCategories(4);
        $categoriesInt = array();
        foreach ($categories as $category) {
            $keys = $category->getAdserverKey(4);
            if ($keys) {
                foreach ($keys as $key) {
                    $categoriesInt[] = (int) $key;
                }
            }
        }
        $placement->properties->category = $categoriesInt;
        $domains[] = $site->getName();
        $placement->properties->domain = $domains;

        $placements[] = $placement;

        $data_publisher->publisher->placements = $placements;

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'exchange/publishers/' . $site->publisher->getAdserverKey($this->adserver->getId()) . $this->tokenUrl);
        $request->setData($data_publisher->publisher);

        $call = new Caller();
        try {
            $res = $call->call($request);
            if (isset($res->id)) {
                $publisherAdk2 = $this->getPublisher($res->id);
                foreach ($publisherAdk2->placements as $siteAdk2) {
                    if ($siteAdk2->name == $site->getName()) {
                        return $siteAdk2->id;
                    }
                }
                return 0;
            } else {
                throw new Exception($res->message);
            }
        } catch (Exception $ex) {
            $this->_manageException('New Site ' . $site->getName() . ': ' . $ex->getMessage());
        }
    }

//Falta!
    public function categorizeSite(Site $site) {
        echo 'OK';
        $publisherAdk2 = $this->getPublisher($site->publisher->getAdserverKey($this->adserver->getId()));
        dd($publisherAdk2);
        foreach ($publisherAdk2->placements as $key => $siteAdk2) {
            if ($siteAdk2->name == $site->getName()) {

                $categoriesArray = array();
                $categories = $site->getArrayOfAdserverCategories($this->adserver->getId());

                foreach ($categories as $category) {
                    $categoriesArray[] = (int) $category;
                }
                dd($categoriesArray);
                $publisherAdk2->placements[$key]->properties->category = $categoriesArray;
                $request = new ApiRequest();
                $request->setMethod('post');
                $request->setToken($this->token);
                $request->setUri(self::SOAP_BASE . 'exchange/publishers/' . $site->publisher->getAdserverKey($this->adserver->getId()) . $this->tokenUrl);
                $request->setData($publisherAdk2);

                $call = new Caller();
                try {
                    $res = $call->call($request);
                    var_dump($res);
                    if (isset($res->id)) {
                        return TRUE;
                    } else {
                        throw new Exception($res->message);
                    }
                } catch (Exception $ex) {
                    $this->_manageException('Categorize Site ' . $site->getName() . ': ' . $ex->getMessage());
                }
            }
        }
    }

//OK
    public function changeSiteOptions(Site $site) {
        return TRUE;
    }

//OK
    public function categorizeSites($sites) {
        foreach ($sites as $siteid) {
            $site = Site::find($siteid->sit_id);
            $publisherAdk2 = $this->getPublisher($site->publisher->getAdserverKey($this->adserver->getId()));
            unset($publisherAdk2->audit);
            unset($publisherAdk2->created);
            unset($publisherAdk2->createdBy);
            unset($publisherAdk2->modifiedBy);
            unset($publisherAdk2->modified);
            unset($publisherAdk2->payee);
            foreach ($publisherAdk2->placements as $key => $siteAdk2) {
                if ($siteAdk2->name == $site->getName()) {
                    $categoriesArray = array();
                    $categories = $site->getArrayOfAdserverCategories($this->adserver->getId());

                    foreach ($categories as $category) {
                        $categoriesArray[] = (int) $category;
                    }
                    $publisherAdk2->placements[$key]->properties->category = $categoriesArray;
                    $request = new ApiRequest();
                    $request->setMethod('post');
                    $request->setToken($this->token);
                    $request->setUri(self::SOAP_BASE . 'exchange/publishers/' . $site->publisher->getAdserverKey($this->adserver->getId()) . $this->tokenUrl);
                    $request->setData($publisherAdk2);

                    $call = new Caller();
                    try {
                        $call->call($request);
                    } catch (Exception $ex) {
                        $this->_manageException('Categorize Site ' . $site->getName() . ': ' . $ex->getMessage());
                    }
                }
            }
            Site::updateSiteSetCategorizedFTrue($site->getId());
            echo "\tcategorizado: " . $site->getName() . " - " . $site->getId() . "\n";
            sleep(2);
        }
    }

//OK
    public function newPlacement(Placement $placement) {
        return $placement->site->getName() . '-' . $placement->size->getName();
    }

//OK
    public function addDefaultTagToPlacement(Placement $placement) {
        return TRUE;
    }

//OK
    public function getAdserverPlacementName($placement) {
        return $placement->getName();
    }

    /*public function getTestReport() {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $count = 0;
        $publishers = Publisher::getByAdserver($this->adserver->getId());
        foreach ($publishers as $publisherId) {
            try {
                $publisher = Publisher::find($publisherId->publisher_id);
                echo "--------------------------------------------\n\n";
                echo "\n\tPUBLISHER: " . $publisher->getName() . ' - ' . $publisher->getId() . "\n";
                $request = new ApiRequest();
                $request->setMethod('get');
                $request->setToken($this->token);
                $request->setUri(self::SOAP_BASE . 'denver/reports/publishers/' . $publisher->getAdserverKey($this->adserver->getId()) . '/publisher_by_placement_by_transaction_type/' . $yesterday . '-' . $yesterday . $this->tokenUrl . '&fmt=json');
                $call = new Caller();
                try {
                    $res = $call->call($request);
                    foreach ($res->data as $value) {
                        echo $value[3] . "\t" . $value[4] . "\t" . $value[5] . "\t" . $value[6] . "\t" . $value[7] . "\t" . $value[10] . "\t" . $value[11] . "\t" . $value[12] . "\n";
                        echo "<pre />";
                    }
                } catch (Exception $ex) {
                    $this->_manageException('Inventory Report of Publisher' . $publisher->getName() . ': ' . $ex->getMessage());
                }
            } catch (Exception $publisherEx) {
                $this->_manageException('Inventory Report (publisher id) ' . $publisherId->publisher_id . ': ' . $publisherEx->getMessage());
            }
        }
        dd();
        return $count;
    }*/

    public function getInventoryReport() {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $count = 0;
        $publishers = Publisher::getByAdserver($this->adserver->getId());
        foreach ($publishers as $publisherId) {
            try {
                $publisher = Publisher::find($publisherId->publisher_id);
                echo "\n\tPUBLISHER: " . $publisher->getName() . ' - ' . $publisher->getId() . "\n";
                $request = new ApiRequest();
                $request->setMethod('get');
                $request->setToken($this->token);
                $request->setUri(self::SOAP_BASE . 'denver/reports/publishers/' . $publisher->getAdserverKey($this->adserver->getId()) . '/publisher_by_placement_by_country_by_size/' . $yesterday . '-' . $yesterday . $this->tokenUrl . '&fmt=json');
                $call = new Caller();
                try {
                    $res = $call->call($request);
                    if (!$res)
                        $this->_manageException('No data!');
                    $count += $this->fillInventory($res->data, $yesterday);
                } catch (Exception $ex) {
                    $this->_manageException('Inventory Report of Publisher' . $publisher->getName() . ': ' . $ex->getMessage());
                }
            } catch (Exception $publisherEx) {
                $this->_manageException('Inventory Report (publisher id) ' . $publisherId->publisher_id . ': ' . $publisherEx->getMessage());
            }
        }
        return $count;
    }

    private function fillInventory($rows, $day) {
        $count = 0;
        //Quita el array de nombre de columnas
        array_shift($rows);
        echo "\n" . sizeof($rows) . " registros en el reporte\n";
        try {
            if (sizeof($rows) > 0) {
                foreach ($rows as $row) {
                    try {
                        if ($row[4] == '') {
                            continue;
                        }
                        $inventory = new Inventory($this->adserver->getId());
                        $inventory->setAdserver($this->adserver->getId());
                        $inventory->setDay($day);
                        $inventory->setPublisherAdserverId($row[0]);
                        $inventory->setPublisherName($row[1]);
                        $inventory->setSiteAdserverId($row[2]);
                        $inventory->setSiteName($row[3]);
                        $inventory->setPlacementName($row[3] . '-' . $row[4]);
                        $inventory->setPlacementAdserverId($row[3] . '-' . $row[4]);
                        $inventory->setCountryName($row[5]);
                        $inventory->setCountryAdserverId($row[5]);
                        $inventory->setImps((float) $row[6]);
                        $inventory->setClicks((float) $row[7]);
                        $inventory->setRevenue((float) $row[9]);
                        $inventory->printRow();
                        $inventory->save();
                        echo "\n\tsaved OK\n";
                        $count++;
                    } catch (Exception $exc) {
                        echo $exc->getMessage() . "\n";
                    }
                }
                echo "\n" . $count . " registros agregados.\n";
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $count;
    }

//Falta!
    public function getReport($data) {
        try {
            $reportData = new stdClass();
            $reportData->report = new stdClass();
            $reportData->report->report_type = 'network_analytics';
            $reportData->report->special_pixel_reporting = false;
            $reportData->report->pivot_report = false;
            $reportData->report->filters[] = array(
                'publisher_id' => Publisher::find($data['publisher_id'])->getAdserverKey(2)
            );
            $reportData->report->start_date = $data['start_date'];
            $reportData->report->end_date = $data['end_date'];
            $reportData->report->timezone = 'UTC';
            $reportData->report->columns = $this->getColumns($data['columns']);

            return $this->downloadReport($reportData);
        } catch (Exception $ex) {
            return $this->_manageException('Get Report by ' . $data['group_by'] . ' of publisher ' . $data['publisher_id'] . ': ' . $ex->getMessage());
        }
    }

    /*     * *
     * Private functions
     */

    private function getPublisher($publisherId) {
        $request = new ApiRequest();
        $request->setMethod('get');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'exchange/publishers/' . $publisherId . $this->tokenUrl);

        $call = new Caller();
        try {
            $res = $call->call($request);
            if (isset($res->status)) {
                return $res;
            } else {
                throw new Exception($res->message);
            }
        } catch (Exception $ex) {
            $this->_manageException('Get Publisher ' . $publisherId . ': ' . $ex->getMessage());
        }
    }

    private function getRevenueShareDecimal($share) {
        $value = number_format(($share / 60), 3);
        $max = number_format((100 / 60), 3);
        $min = number_format((0 / 60), 3);
        if ($value > $max) {
            return $max - 0.001;
        } elseif ($value < $min) {
            return $min + 0.001;
        } else {
            return $value;
        }
    }

    private function getAdserver() {
        return Adserver::where('adv_class_name', get_class($this))->first();
    }

    private function _manageException($ex) {
// open log file
        $filename = public_path() . "/logs/adk2.log";
        $fh = fopen($filename, "a") or die();
        fwrite($fh, date("d-m-Y, H:i") . " - $ex\n") or die();
        fclose($fh);
    }

}
