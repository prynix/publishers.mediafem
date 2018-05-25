<?php

class AppnexusApi {

    const SOAP_BASE = 'http://api.appnexus.com/';

    private $token;
    private $adserver;

    function __construct() {
        $this->adserver = $this->getAdserver();
        $this->token = $this->adserver->getToken();
    }

    public function refreshToken() {

        $data_auth = new stdClass();
        $data_auth->auth = new stdClass();
        $data_auth->auth->username = $this->adserver->getUsername();
        $data_auth->auth->password = $this->adserver->getPassword();

        $request_auth = new ApiRequest();
        $request_auth->setMethod('post');
        $request_auth->setUri(self::SOAP_BASE . 'auth');
        $request_auth->setData($data_auth);

        $call = new Caller();

        try {
            $res = $call->call($request_auth);
            return $res->response->token;
        } catch (Exception $ex) {
            $this->_manageException('Refresh token: ' . $ex->getMessage());
        }
    }

    public function getActualToken() {
        $token = ['token' => $this->token, 'creation_time' => $this->adserver->getTokenSet()];
        return $token;
    }

    public function createMediaBuyer($admin) {
        return $admin->user->getEmail();
    }

    public function newPublisher(Publisher $publisher) {
        $data_publisher = new stdClass();
        $data_publisher->publisher = new stdClass();
        $data_publisher->publisher->name = $publisher->getName() . '(ADT)' . $publisher->user->getEmail();
        $data_publisher->publisher->state = 'active';
        $data_publisher->publisher->billing_address1 = $publisher->user->profile->getAddress();
        $data_publisher->publisher->billing_city = $publisher->user->profile->getCity();
        $data_publisher->publisher->billing_zip = $publisher->user->profile->getZipCode();
        $data_publisher->publisher->allow_cpa_external = true;
        $data_publisher->publisher->allow_cpc_external = true;
        $data_publisher->publisher->external_cpa_bias_pct = 0;
        $data_publisher->publisher->external_cpc_bias_pct = 5;
        //$data_publisher->publisher->max_learn_pct = $data['max_learn'];

        $labels[] = array("value" => "Adtomatik para Sitios", "id" => 2, "name" => "Salesperson");
        $labels[] = array("value" => "Adtomatik para Sitios", "id" => 4, "name" => "Account Manager");

        $data_publisher->publisher->labels = $labels;

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'publisher?create_default_placement=false');
        $request->setData($data_publisher);

        $call = new Caller();
        try {
            $res = $call->call($request);

            return array(
                'publisher_key' => $res->response->publisher->id
            );
        } catch (Exception $ex) {
            $this->_manageException('New Publisher ' . $publisher->getName() . ': ' . $ex->getMessage());
        }
    }

    public function createPaymentRule($id_publisher) {
        $data_payment_rule = new stdClass();
        $payment_rule = "payment-rule";
        $data_payment_rule->$payment_rule = new stdClass();
        $data_payment_rule->$payment_rule->name = "payment_rule_base";
        $data_payment_rule->$payment_rule->pricing_type = "revshare";
        $decimal = floor(Constant::value('default_revenue_share_appnexus'));
        $double = str_replace(',', '.', ($decimal / 100));
        $data_payment_rule->$payment_rule->revshare = $double;
        $data_payment_rule->$payment_rule->priority = 1;
        $data_payment_rule->$payment_rule->state = "active";

        $request_payment_rule = new ApiRequest();
        $request_payment_rule->setMethod('post');
        $request_payment_rule->setToken($this->token);
        $request_payment_rule->setUri(self::SOAP_BASE . '/payment-rule?publisher_id=' . $id_publisher);
        $request_payment_rule->setData($data_payment_rule);

        $call = new Caller();
        try {
            $res_payment_rule = $call->call($request_payment_rule);

            return $res_payment_rule->response->id;
        } catch (Exception $ex) {
            $this->_manageException('New Payment rule: ' . $ex->getMessage());
        }
    }

    public function createProfile($id_publisher, $id_placement, $name_placement, $id_country) {
        $data_profile = new stdClass();
        $profile = "profile";
        $data_profile->$profile = new stdClass();
        $data_profile->$profile->description = $name_placement . ' - ' . $id_country;
        //$data_profile->$profile->code = $id_placement.'_'.$id_country;
        $country_target = new stdClass();
        $country_target->country = $id_country;
        $country_targets[] = $country_target;
        $data_profile->$profile->country_targets = $country_targets;
        $placement_target = new stdClass();
        $placement_target->id = $id_placement;
        $placement_target->action = 'include';
        $placement_targets[] = $placement_target;
        $data_profile->$profile->placement_targets = $placement_targets;
        $data_profile->$profile->country_action = 'include';
        $data_profile->$profile->inventory_action = 'include';

        $request_profile = new ApiRequest();
        $request_profile->setMethod('post');
        $request_profile->setToken($this->token);
        $request_profile->setUri(self::SOAP_BASE . '/profile?publisher_id=' . $id_publisher);
        $request_profile->setData($data_profile);

        $call = new Caller();
        try {
            $res_profile = $call->call($request_profile);

            return $res_profile->response->id;
        } catch (Exception $ex) {
            $this->_manageException('New Profile: ' . $ex->getMessage());
        }
    }

    public function createConditionalPaymentRule($id_publisher, $id_placement, $name_placement, $id_country, $share) {
        $data_payment_rule = new stdClass();
        $payment_rule = "payment-rule";
        $data_payment_rule->$payment_rule = new stdClass();
        $data_payment_rule->$payment_rule->name = $name_placement . ' - ' . $id_country;
        $data_payment_rule->$payment_rule->code = $id_placement . '_' . $id_country;
        $data_payment_rule->$payment_rule->pricing_type = "revshare";
        $data_payment_rule->$payment_rule->revshare = $share;
        $data_payment_rule->$payment_rule->priority = 9;
        $data_payment_rule->$payment_rule->state = "active";
        $data_payment_rule->$payment_rule->profile_id = $this->createProfile($id_publisher, $id_placement, $name_placement, $id_country);

        $request_payment_rule = new ApiRequest();
        $request_payment_rule->setMethod('post');
        $request_payment_rule->setToken($this->token);
        $request_payment_rule->setUri(self::SOAP_BASE . '/payment-rule?publisher_id=' . $id_publisher);
        $request_payment_rule->setData($data_payment_rule);

        $call = new Caller();
        try {
            $res_payment_rule = $call->call($request_payment_rule);

            return $res_payment_rule->response->id;
        } catch (Exception $ex) {
            $this->_manageException('New Conditional Payment rule (' . $name_placement . ' - ' . $id_country . '): ' . $ex->getMessage());
        }
    }

    public function editConditionalPaymentRule($publisher_key, $payment_rule_key, $share) {
        $data_payment_rule = new stdClass();
        $payment_rule = "payment-rule";
        $data_payment_rule->$payment_rule = new stdClass();
        $data_payment_rule->$payment_rule->revshare = $share;

        $request_payment_rule = new ApiRequest();
        $request_payment_rule->setMethod('put');
        $request_payment_rule->setToken($this->token);
        $request_payment_rule->setUri(self::SOAP_BASE . '/payment-rule?id=' . $payment_rule_key . '&publisher_id=' . $publisher_key);
        $request_payment_rule->setData($data_payment_rule);

        $call = new Caller();
        try {
            $res_payment_rule = $call->call($request_payment_rule);
            return $res_payment_rule;
        } catch (Exception $ex) {
            $this->_manageException('Edit Conditional Payment Rule id(' . $payment_rule_key . '): ' . $ex->getMessage());
        }
    }

    public function assignPaymentRule($id_publisher) {
        // creo la payment rule
        $payment_rule = $this->createPaymentRule($id_publisher);

        // asigno al publisher la payment rule creada
        $data_publisher_payment_rule = new stdClass();
        $data_publisher_payment_rule->publisher = new stdClass();
        $data_publisher_payment_rule->publisher->base_payment_rule_id = $payment_rule;

        $request_publisher_payment_rule = new ApiRequest();
        $request_publisher_payment_rule->setMethod('put');
        $request_publisher_payment_rule->setToken($this->token);
        $request_publisher_payment_rule->setUri(self::SOAP_BASE . '/publisher?id=' . $id_publisher);
        $request_publisher_payment_rule->setData($data_publisher_payment_rule);

        $call = new Caller();
        try {
            $call->call($request_publisher_payment_rule);

            return TRUE;
        } catch (Exception $ex) {
            $this->_manageException('Assign payment rule to ' . $id_publisher . ': ' . $ex->getMessage());
        }
    }

    public function getPaymentRuleId($key_publisher) {

        $request_publisher_payment_rule = new ApiRequest();
        $request_publisher_payment_rule->setMethod('get');
        $request_publisher_payment_rule->setToken($this->token);
        $request_publisher_payment_rule->setUri(self::SOAP_BASE . '/publisher?id=' . $key_publisher);

        $call = new Caller();
        try {
            $res = $call->call($request_publisher_payment_rule);
            return $res->response->publisher->base_payment_rule_id;
        } catch (Exception $ex) {
            $this->_manageException('Get payment rule of ' . $key_publisher . ' publisher: ' . $ex->getMessage());
        }
    }

    /* public function getPlacement($id) {

      $request_publisher_payment_rule = new ApiRequest();
      $request_publisher_payment_rule->setMethod('get');
      $request_publisher_payment_rule->setToken($this->token);
      $request_publisher_payment_rule->setUri(self::SOAP_BASE . '/creative?id=' . $id);

      $call = new Caller();
      try {
      $res = $call->call($request_publisher_payment_rule);
      var_dump($res->response);
      } catch (Exception $ex) {
      $this->_manageException('Get Placement ' . $id . ': ' . $ex->getMessage());
      }
      } */

    public function newDefaultCreative(Size $size, $publisherKey) {
        $data_default_creative = new stdClass();
        $data_default_creative->creative = new stdClass();
        $data_default_creative->creative->name = $size->getName();
        $data_default_creative->creative->format = 'raw-js';
        $data_default_creative->creative->template = new stdClass();
        $data_default_creative->creative->template->id = '5';
        $data_default_creative->creative->width = $size->getWidth();
        $data_default_creative->creative->height = $size->getHeight();
        $data_default_creative->creative->allow_audit = FALSE;
        $data_default_creative->creative->audit_status = 'no_audit';
        $data_default_creative->creative->original_content = '';
        $data_default_creative->creative->track_clicks = FALSE;
        $data_default_creative->creative->track_clicks = FALSE;
        $data_default_creative->creative->content = '<script src="http://www.googletagservices.com/tag/js/gpt.js">
                                    googletag.pubads().enableSyncRendering();
                                    googletag.enableServices();
                                    googletag.pubads().display(\'/25379366/Passback_' . $size->getWidth() . 'x' . $size->getHeight() . '\', [' . $size->getWidth() . ', ' . $size->getHeight() . '], \'div-gpt-ad-${CACHEBUSTER}-0\',\'${CLICK_URL}\');
                                    </script>';

        $request_default_creative = new ApiRequest();
        $request_default_creative->setMethod('post');
        $request_default_creative->setToken($this->token);
        $request_default_creative->setUri(self::SOAP_BASE . '/creative?publisher_id=' . $publisherKey);
        $request_default_creative->setData($data_default_creative);

        $call = new Caller();
        try {
            $res_default_creative = $call->call($request_default_creative);
            return $res_default_creative->response->id;
        } catch (Exception $ex) {
            $this->_manageException('New Default Creative for ' . $publisherKey . ' publisher:' . $ex->getMessage());
            echo $ex->getMessage();
            return NULL;
        }
    }

    public function adjustRevShare(PublisherOptimization $po) {

        try {
            $publisher = Publisher::find($po->getPublisherId());
            $key_publisher = $publisher->getAdserverKey($this->adserver->getId());
            $payment_rule_id = $po->getPaymentRule();
            $shareDouble = $po->getDoubleNewRevShare();
            $share = floor($po->getPublisherDueShare());
            if (!$payment_rule_id) {
                $country = Country::find($po->country->cnt_id);
                $key_country = $country->getAdserverKey($this->adserver->getId());
                $placement = Placement::find($po->placement->getId());
                $key_placement = $placement->getKey();
                $payment_rule_id = $this->createConditionalPaymentRule($key_publisher, $key_placement, $placement->getName(), $key_country, $shareDouble);
                echo "\n Payment rule nuevo " . $payment_rule_id . "\n";
                PaymentRule::newPaymentRule($po->placement->getId(), $po->country->cnt_id, $payment_rule_id, $placement->getName() . ' - ' . $po->country->cnt_id, $share);
            } else {
                echo "\n Edita Payment rule " . $payment_rule_id . "\n";
                $this->editConditionalPaymentRule($key_publisher, $payment_rule_id->payment_rule_id, $shareDouble);
                $payment_rule = PaymentRule::find($payment_rule_id->id);
                $payment_rule->share = $share;
                $payment_rule->save();
            }
            return true;
        } catch (Exception $ex) {
            $this->_manageException('Adjust Rev Share of payment rule of ' . $po->getPublisherId() . ' publisher: ' . $ex->getMessage());
            return false;
        }
    }

    public function assignMediaBuyer(Publisher $publisher) {
        $data_publisher = new stdClass();
        $data_publisher->publisher = new stdClass();
        $accountManager = new stdClass();
        $salesperson = new stdClass();

        $accountManager->value = $publisher->mediaBuyer->getAdserverKey($this->adserver->getId());
        $accountManager->id = 4;
        $salesperson->value = $publisher->mediaBuyer->getAdserverKey($this->adserver->getId());
        $salesperson->id = 2;

        $label = [$accountManager, $salesperson];

        $data_publisher->publisher->labels = $label;

        $request = new ApiRequest();
        $request->setMethod('put');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'publisher?id=' . $publisher->getAdserverKey($this->adserver->getId()));
        $request->setData($data_publisher);

        $call = new Caller();
        try {
            $call->call($request);
            return true;
        } catch (Exception $ex) {
            $this->_manageException('Assign media buyer to ' . $publisher->getName() . ': ' . $ex->getMessage());
            return false;
        }
    }

    public function newSite(Site $site) {

        //$inventory_attributes[] = array("id" => 14);

        $categoriesArray = array();
        $categories = $site->getArrayOfAdserverCategories($this->adserver->getId());
        $countCategories = 0;
        foreach ($categories as $category) {
            if ($countCategories == 20)
                break;
            $categoriesArray[] = array('id' => $category);
            $countCategories+=1;
        }

        $data = new stdClass();
        $data->site = new stdClass();
        $data->site->name = $site->getName();
        $data->site->url = $site->getName();
        $data->site->content_categories = $categoriesArray;
        $data->site->audited = true;
        $data->site->intended_audience = "general";
        $data->site->inventory_attributes = NULL; //$inventory_attributes;
        //dd($categoriesArray);
        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'site?publisher_id=' . $site->publisher->getAdserverKey($this->adserver->getId()));
        $request->setData($data);
        $call = new Caller();
        try {
            $res = $call->call($request);
            return $res->response->site->id;
        } catch (Exception $ex) {
            $this->_manageException('New Site ' . $site->getName() . ': ' . $ex->getMessage());
        }
    }

    public function categorizeSite(Site $site) {

        $categoriesArray = array();
        $categories = $site->getArrayOfAdserverCategories($this->adserver->getId());

        foreach ($categories as $category) {
            $categoriesArray[] = array('id' => $category);
        }

        $data = new stdClass();
        $data->site = new stdClass();
        $data->site->content_categories = $categoriesArray;

        $request = new ApiRequest();
        $request->setMethod('put');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'site?id=' . $site->getAdserverKey($this->adserver->getId()));
        $request->setData($data);
        $call = new Caller();
        try {
            $call->call($request);
        } catch (Exception $ex) {
            $this->_manageException('Categorize Site ' . $site->getName() . ': ' . $ex->getMessage());
        }
    }

    public function changeSiteOptions(Site $site) {
        $data = new stdClass();
        $data->site = new stdClass();
        $data->site->inventory_attributes = NULL;

        $request = new ApiRequest();
        $request->setMethod('put');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'site?id=' . $site->getAdserverKey($this->adserver->getId()));
        $request->setData($data);
        $call = new Caller();
        try {
            $call->call($request);
            return TRUE;
        } catch (Exception $ex) {
            $this->_manageException('Change Inventory Self-Classification Site ' . $site->getName() . ': ' . $ex->getMessage());
            return FALSE;
        }
    }

    public function categorizeSites($sites) {
        foreach ($sites as $siteid) {
            $site = Site::find($siteid->sit_id);
            $categoriesArray = array();
            $categories = $site->getArrayOfAdserverCategories($this->adserver->getId());

            foreach ($categories as $category) {
                $categoriesArray[] = array('id' => $category);
            }
            
            $categoriesArray[] = array('id' => '35311');
            
            $data = new stdClass();
            $data->site = new stdClass();
            $data->site->content_categories = $categoriesArray;

            $request = new ApiRequest();
            $request->setMethod('put');
            $request->setToken($this->token);
            $request->setUri(self::SOAP_BASE . 'site?id=' . $site->getAdserverKey($this->adserver->getId()));
            $request->setData($data);
            $call = new Caller();
            try {
                $call->call($request);
            } catch (Exception $ex) {
                $this->_manageException('Categorize Sites: ' . $ex->getMessage());
            }
            Site::updateSiteSetCategorizedFTrue($site->getId());
            echo "\tcategorizado: " . $site->getName() . " - " . $site->getId() . "\n";
            sleep(2);
        }
    }

    public function newPlacement(Placement $placement) {

        $data = new stdClass();
        $data->placement = new stdClass();
        $data->placement->name = $placement->getName();
        $data->placement->exclusive = false;
        $data->placement->height = $placement->size->getHeight();
        $data->placement->width = $placement->size->getWidth();
        $data->placement->intended_audience = "general";
        $data->placement->audited = true;
        $data->placement->audit_level = "placement";
        $data->placement->intended_audience = "general";
        $defaultCreative = $this->newDefaultCreative($placement->size, $placement->site->publisher->getAdserverKey($this->adserver->getId()));
        if ($defaultCreative)
            $data->placement->default_creative_id = $defaultCreative;

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'placement?site_id=' . $placement->site->getAdserverKey($this->adserver->getId()));
        $request->setData($data);
        $call = new Caller();
        try {
            $res = $call->call($request);
            return $res->response->placement->id;
        } catch (Exception $ex) {
            $this->_manageException('New Placement ' . $placement->getName() . ': ' . $ex->getMessage());
        }
    }

    public function deletePlacement(Placement $placement) {
        $request = new ApiRequest();
        $request->setMethod('delete');
        $request->setToken($this->token);
        echo "\tPlacement a eliminar: " . $placement->getKey() . "\n";
        echo "\tdel publisher: " . $placement->site->publisher->getAdserverKey($this->adserver->getId()) . "\n";
        $request->setUri(self::SOAP_BASE . 'placement?id=' . $placement->getKey() . '&publisher_id=' . $placement->site->publisher->getAdserverKey($this->adserver->getId()));
        $call = new Caller();
        try {
            $trys = 10;
            $res = $call->call($request);
            while ($res == "RATE_EXCEEDED" && $trys >= 0) {
                echo "\t\t\tIntento nro: " . 10 - $trys . "\n";
                $res = $call->call($request);
                $trys = $trys - 1;
            }
            if ($res->response->status == "OK") {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            $this->_manageException('Delete Placement ' . $placement->getName() . ': ' . $ex->getMessage());
        }
    }

    public function deactivatePlacement(Placement $placement) {
        $data = new stdClass();
        $data->placement = new stdClass();
        $data->placement->state = 'inactive';

        $request = new ApiRequest();
        $request->setMethod('put');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'placement?id=' . $placement->getKey() . '&publisher_id=' . $placement->site->publisher->getAdserverKey($this->adserver->getId()));
        $request->setData($data);
        $call = new Caller();
        try {
            $res = $call->call($request);
            return TRUE;
        } catch (Exception $ex) {
            $this->_manageException('Deactivate Placement ' . $placement->getName() . ': ' . $ex->getMessage());
            return FALSE;
        }
    }

    public function addDefaultTagToPlacement(Placement $placement) {
        $data = new stdClass();
        $data->placement = new stdClass();
        $defaultCreative = $this->newDefaultCreative($placement->size, $placement->site->publisher->getAdserverKey($this->adserver->getId()));
        echo "\tCreative: " . $defaultCreative;
        if ($defaultCreative)
            $data->placement->default_creative_id = $defaultCreative;

        $request = new ApiRequest();
        $request->setMethod('put');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'placement?id=' . $placement->getKey() . '&publisher_id=' . $placement->site->publisher->getAdserverKey($this->adserver->getId()));
        $request->setData($data);
        $call = new Caller();
        try {
            $res = $call->call($request);
            return TRUE;
        } catch (Exception $ex) {
            $this->_manageException('Add Default Tag to Placement ' . $placement->getName() . ': ' . $ex->getMessage());
            return FALSE;
        }
    }

    public function getAdserverPlacementName($placement) {
        return $placement->getName();
    }

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

    public function getMediaBuyerReport(Administrator $admin, $range = 'last_month') {
        try {
            $data = new stdClass();
            $data->report = new stdClass();
            $data->report->report_type = "network_analytics";

            $data->report->special_pixel_reporting = false;
            $data->report->pivot_report = false;

            $publishers = Publisher::getByAdserverAndMediaBuyer(2, $admin->getId());
            echo "\tPublishers asignados: " . count($publishers) . "\n";
            if (count($publishers) < 1) {
                return true;
            }
            $listPublishersKey = array();
            //Get Publishers IDs
            foreach ($publishers as $publisher) {
                $listPublishersKey[] = $publisher->adv_pbl_adserver_key;
            }

            $data->report->filters[] = array(
                'publisher_id' => $listPublishersKey
            );

            $data->report->columns = array(
                'publisher_id'
                , 'imps'
                , 'imps_blank'
                , 'imps_psa'
                , 'imps_psa_error'
                , 'imps_default_error'
                , 'imps_default_bidder'
                , 'imps_kept'
                , 'imps_resold'
                , 'imps_rtb'
                , 'revenue'
                , 'reseller_revenue'
                , 'booked_revenue'
                , 'cost'
                , 'profit'
            );

            if ($range == 'last_month') {
                $data->report->report_interval = 'last_month';
                $range = date('Y-m-d', strtotime("first day of last month"));
            } else {
                $data->report->start_date = date('Y-m-01 00:00:00', strtotime($range));
                $data->report->end_date = date('Y-m-01 00:00:00', strtotime(date("Y-m-d", strtotime($range)) . " +1 month"));
            }

            $data->report->timezone = 'UTC';

            $request = new ApiRequest();
            $request->setMethod('post');
            $request->setToken($this->token);
            $request->setUri(self::SOAP_BASE . 'report');
            $request->setData($data);
            $call = new Caller();

            $res = $call->call($request);

            if (!isset($res->response->report_id)) {
                return FALSE;
            }
            $id_report = $res->response->report_id;

            $url = self::getReportUrl($id_report, 250);

            unset($request);
            unset($res);

            $request = new ApiRequest();
            $request->setMethod('get');
            $request->setToken($this->token);
            $request->setUri(self::SOAP_BASE . $url);

            $request->setData($request);
            $call = new Caller();
            $res = $call->downloadReport($request);
            $report = $this->processReport($res, false);
            //Variables
            $cost = 0;
            $profit = 0;
            $imps = 0;
            $revenue = 0;
            $revenue_resold = 0;
            $revenue_booked = 0;
            $profit_adjusted = 0;
            $usd_adjust = 0;
            $adserving = 0;

            if (count($report) < 1) {
                return FALSE;
            }
            foreach ($report as $row) {
                $po = new PublisherOptimization();

                $po->setImps($row->imps);
                $po->setImpsBlank($row->imps_blank);
                $po->setImpsPsa($row->imps_psa);
                $po->setImpsPsaError($row->imps_psa_error);
                $po->setImpsDefaultError($row->imps_default_error);
                $po->setImpsDefaultBidder($row->imps_default_bidder);
                $po->setImpsKept($row->imps_kept);
                $po->setImpsResold($row->imps_resold);
                $po->setImpsRtb($row->imps_rtb);
                $po->setRevenue($row->revenue);
                $po->setResellerRevenue($row->reseller_revenue);
                $po->setCost($row->cost);
                $po->setProfit($row->profit);

                $po->actualize(FALSE);

                $cost += $row->cost;
                $imps += $row->imps;
                $revenue += $row->revenue;
                $revenue_resold += $row->reseller_revenue;
                $revenue_booked += $row->booked_revenue;
                $profit += $row->profit;
                $profit_adjusted += $po->profitAdjusted;
                $usd_adjust += $po->adjustmentUsd;
                $adserving += $po->adServing;
            }
            $mbc = new MediaBuyerCommission();
            $mbc->setAdserver($this->adserver->getId());
            $mbc->setAdministrator($admin->getId());
            $mbc->setCost($cost);
            $mbc->setPeriod(date('Y-m-01', strtotime($range)));
            $mbc->setImps($imps);
            $mbc->setRevenue($revenue);
            $mbc->setProfit($profit);
            $mbc->mbc_profit_adjusted = $profit_adjusted;
            $mbc->mbc_adjustment_usd = $usd_adjust;
            $mbc->mbc_adserving = $adserving;
            $mbc->mbc_reseller_revenue = $revenue_resold;
            $mbc->mbc_booked_revenue = $revenue_booked;
            $mbc->setCommission($admin->getRevenueShare('mediabuyer'));
            $mbc->save();
            echo "\t\tRevenue: $" . $mbc->getRevenue() . "\n";
            echo "\t\tProfit: $" . $mbc->getProfit() . "\n";
            echo "\t\tCosto: $" . $mbc->getCost() . "\n";
            echo "\t\tComision: $" . $mbc->getCommission() . "\n";
            return TRUE;
        } catch (Exception $ex) {
            $this->_manageException('Get Report Media Buyer Commission: ' . $ex->getMessage());
            echo $ex->getMessage();
            return FALSE;
        }
    }

    /*     * *
     * Private functions
     */

    private function downloadReport($data) {
        try {
            // genero el reporte en appnexus
            $request = new ApiRequest();
            $request->setMethod('post');
            $request->setToken($this->token);
            $request->setUri(self::SOAP_BASE . 'report');
            $request->setData($data);
            $call = new Caller();
            $intento = 0;
            do {
                if ($intento > 0)
                    sleep(3);

                $res = $call->call($request);

                $intento++;
            }while ($res === 'Request failed' || !$res);

            // si todo salio bien descargo el reporte
            if ($res->response->status === 'OK') {
                unset($request);
                $request = new ApiRequest();
                $request->setMethod('get');
                $request->setToken($this->token);
                $request->setUri(self::SOAP_BASE . 'report?id=' . $res->response->report_id);
                $request->setData($request);
                $call = new Caller();

                $intento = 0;
                do {
                    if ($intento > 0)
                        sleep(3);

                    $res = $call->call($request);

                    $intento++;
                }while ($res === 'Request failed' || !isset($res->response->report->url));

                $url_download = $res->response->report->url;

                unset($request);
                $request = new ApiRequest();
                $request->setMethod('get');
                $request->setToken($this->token);
                $request->setUri(self::SOAP_BASE . '/' . $url_download);
                $request->setData($request);
                $call = new Caller();

                $intento = 0;
                do {
                    if ($intento > 0)
                        sleep(3);

                    $datos = $call->downloadReport($request);

                    $intento++;
                }while ($datos === 'Request failed');

                return $this->processReport($datos);
            }

            // no hay resultados
            return FALSE;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    private function processReport($datos, $defaultColumns = true) {
        $rows = explode("\n", $datos);

        $arr_data = $results = NULL;

        for ($i = 1; $i < sizeof($rows); $i++) {
            if (strlen($rows[$i]) > 0)
                $arr_data[] = $rows[$i];
        }
        $cont = 0;

        if ($defaultColumns)
            $columns = $this->setColumns(explode(",", $rows[0]));
        else {
            $columns = explode(",", $rows[0]);
            foreach ($columns as &$column) {
                $column = trim($column);
            }
        }
        if (sizeof($arr_data)) {
            foreach ($arr_data as $report) {
                $campos = explode(",", $report);

                $dat = new stdClass();

                $a = 0;
                foreach ($campos as $campo) {
                    $dat->$columns[$a] = str_replace('"', '', trim($campo));
                    $a++;
                }

                $results[] = $dat;
            }

            return $results;
        }

        return NULL;
    }

    private function getColumns($setColumns) {
        $adserverColumns = array(
            // anuncio
            'placement_name' => 'placement_name',
            // pais
            'country_name' => 'geo_country_name',
            // sitio
            'site_name' => 'site_name',
            // tamano
            'size_name' => 'size',
            // dia
            'day' => 'day',
            // mes
            'month' => 'month',
        );

        foreach ($setColumns as $column) {
            if (isset($adserverColumns[$column]))
                $columns[] = $adserverColumns[$column];
        }

        $columns[] = 'imps';
        $columns[] = 'clicks';
        $columns[] = 'ctr';
        $columns[] = 'cpm';
        $columns[] = 'cost';

        return $columns;
    }

    private function setColumns($setColumns) {
        $adserverColumns = array(
            // anuncio
            'placement_name' => 'placement_name',
            // pais
            'geo_country_name' => 'country_name',
            // sitio
            'site_name' => 'site_name',
            // tamano
            'size' => 'size_name',
            // dia
            'day' => 'day',
            // mes
            'month' => 'month'
        );

        foreach ($setColumns as $column) {
            if (isset($adserverColumns[$column]))
                $columns[] = $adserverColumns[$column];
        }

        $columns[] = 'imps';
        $columns[] = 'clicks';
        $columns[] = 'ctr';
        $columns[] = 'cpm';
        $columns[] = 'revenue';

        return $columns;
    }

    public function getPublishersOptimization($publishers_keys) {
        $data = new stdClass();
        $data->report = new stdClass();
        $data->report->report_type = "network_analytics";

        $data->report->special_pixel_reporting = false;
        $data->report->pivot_report = false;

        foreach ($publishers_keys as $row) {
            $filtro_publishers[] = $row->adv_pbl_adserver_key;
        }

        $data->report->filters[] = array(
            'publisher_id' => $filtro_publishers
        );

        $data->report->columns = array(
            'publisher_id'
            , 'publisher_name'
            , 'site_id'
            , 'site_name'
            , 'placement_id'
            , 'placement_name'
            , 'geo_country'
            , 'imps'
            , 'imps_blank'
            , 'imps_psa'
            , 'imps_psa_error'
            , 'imps_default_error'
            , 'imps_default_bidder'
            , 'imps_kept'
            , 'imps_resold'
            , 'imps_rtb'
            , 'revenue'
            , 'reseller_revenue'
            , 'cost'
            , 'profit'
        );

        $data->report->report_interval = 'yesterday';

        $data->report->timezone = 'UTC';

        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . 'report');
        $request->setData($data);

        $call = new Caller();
        try {
            $res = $call->call($request);

            $id_report = $res->response->report_id;

            $url = self::getReportUrl($id_report, 250);

            unset($request);
            unset($res);

            $request = new ApiRequest();
            $request->setMethod('get');
            $request->setToken($this->token);
            $request->setUri(self::SOAP_BASE . $url);

            $request->setData($request);
            $call = new Caller();

            $res = $call->downloadReport($request);
            return $this->processReport($res, false);
        } catch (Exception $ex) {
            $this->_manageException('Resold rule: ' . $ex->getMessage());
        }
    }

    private function getReportUrl($reportId, $trys) {
        $request = new ApiRequest();
        $request->setMethod('get');
        $request->setToken($this->token);
        $request->setUri(self::SOAP_BASE . '/report?id=' . $reportId);
        $request->setData($request);
        $call = new Caller();
        try {
            $res = $call->call($request);
            $status = $res->response->execution_status;

            if ($status == "ready" && $res->response->report->url != "") {
                return $res->response->report->url;
            } elseif ($trys > 0) {
                $trys -= 1;
                sleep(5);
                return self::getReportUrl($reportId, $trys);
            } else {
                throw new Exception('Supero los 250 intentos de descargar el reporte Resold Tax');
            }
        } catch (Exception $ex) {
            $this->_manageException('Resold Tax rule: ' . $ex->getMessage());
        }
    }

    private function getAdserver() {
        return Adserver::where('adv_class_name', get_class($this))->first();
    }

    private function _manageException($ex) {
        // open log file
        $filename = public_path() . "/logs/appnexus.log";
        $fh = fopen($filename, "a") or die();
        fwrite($fh, date("d-m-Y, H:i") . " - $ex\n") or die();
        fclose($fh);
    }

}
