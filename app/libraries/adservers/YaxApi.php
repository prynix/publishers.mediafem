<?php

class YaxApi {

    //const SOAP_BASE = 'https://api.yieldmanager.com/api-1.38/';

    const SOAP_BASE = 'https://api-test.yieldmanager.com/api-1.38/';

    private $entity_client, $entity_site, $entity_section, $entity_line_item, $entity_insertion_order, $entity_passback;
    private static $entity_report;
    private $token;
    private $adserver;

    function __construct() {
        $this->adserver = $this->getAdserver();
        $this->token = $this->adserver->getToken();
    }

    private function entityClient() {
        try {
            $this->entity_client = new SoapClient(self::SOAP_BASE . 'entity.php?wsdl');
        } catch (Exception $e) {
            sleep(5);
            $this->entityClient();
        }
    }

    private function entitySite() {
        try {
            $this->entity_site = new SoapClient(self::SOAP_BASE . 'site.php?wsdl');
        } catch (Exception $e) {
            sleep(5);
            $this->entitySite();
        }
    }

    private function entitySection() {
        try {
            $this->entity_section = new SoapClient(self::SOAP_BASE . 'section.php?wsdl');
        } catch (Exception $e) {
            sleep(5);
            $this->entitySection();
        }
    }

    private function entityLineItem() {
        try {
            $this->entity_line_item = new SoapClient(self::SOAP_BASE . 'line_item.php?wsdl');
        } catch (Exception $e) {
            sleep(5);
            $this->entityLineItem();
        }
    }

    private function entityInsertionOrder() {
        try {
            $this->entity_insertion_order = new SoapClient(self::SOAP_BASE . 'insertion_order.php?wsdl');
        } catch (Exception $e) {
            sleep(5);
            $this->entityInsertionOrder();
        }
    }

    private static function entityReport() {
        try {
            self::$entity_report = new SoapClient(self::SOAP_BASE . 'report.php?wsdl');
        } catch (Exception $e) {
            sleep(5);
            self::entityReport();
        }
    }

    private function entityPassback() {
        try {
            $this->entity_passback = new SoapClient(self::SOAP_BASE . 'passback.php?wsdl');
        } catch (Exception $e) {
            sleep(5);
            $this->entityPassback();
        }
    }

    public function refreshToken() {
        $contact_client = new SoapClient(self::SOAP_BASE . 'contact.php?wsdl');
        try {
            $token = $contact_client->login($this->adserver->getUsername(), $this->adserver->getPassword());
            return $token;
        } catch (Exception $ex) {
            $this->_manageException('New token: ' . $ex->getMessage());
        }
    }

    public function getActualToken() {
        $contact_client = new SoapClient(self::SOAP_BASE . 'contact.php?wsdl');
        try {
            $session = $contact_client->getActiveSessions($this->adserver->getUsername(), $this->adserver->getPassword());
            $token = ['token' => $session[0]->token, 'creation_time' => $session[0]->creation_time];
            return $token;
        } catch (Exception $ex) {
            $this->_manageException('Get active token: ' . $ex->getMessage());
        }
    }

    public function newPublisher(Publisher $publisher) {
        $this->entityClient();
        $data_publisher = new stdClass();
        $contact = new stdClass();
        try {
            $data_publisher->entity_type = 'Publisher';
            $data_publisher->name = $publisher->getName() . '_' . $publisher->user->getId();
            $data_publisher->active = true;

            $contact->email = $publisher->user->getEmail();

            $publisherId = $this->entity_client->add($this->token, $data_publisher, $contact);

            //Insertion Order
            $this->entityInsertionOrder();
            $insertion_order = new stdClass();
            $insertion_order->description = "Order MediaFem";
            $insertion_order->seller_entity_id = $publisherId;
            $insertion_order->buyer_entity_id = 983638; //MediaFem
            $insertion_order->buyer_approved = true;
            $insertion_order->seller_approved = true;
            $insertionOrderId = $this->entity_insertion_order->add($this->token, $insertion_order);

            //Line Item
            $this->entityLineItem();
            $line_item = new stdClass();
            $line_item->description = 'LineItem';
            $line_item->insertion_order_id = $insertionOrderId;
            $line_item->pricing_type = 'Revenue Share';
            $line_item->amount = 60;
            $line_item->delivery_type = 'ASAP';
            $line_item->active = 1;
            $this->entity_line_item->add($this->token, $line_item);

            //Passbacks
            $this->entityPassback();
            $sizes = Size::getAdserverSizes($this->adserver->getId());
            $publisherIds[] = $publisherId;

            foreach ($sizes as $size) {

                $passback = new stdClass();
                $passback->description = $size->getName() . '_' . $publisher->getName() . '_' . $publisher->user->getId();
                $passback->owner_type = 'publisher';
                $passback->size_id = $size->getAdserverKey($this->adserver->getId());
                $passback->owner_id = $publisherIds;
                $passback->entity_id = $publisherId;
                $passback->passback_type = 'redirect';
                $passback->media_type = 'Javascript';
                $passback->is_secure = false;
                $passback->content = '<script src="http://www.googletagservices.com/tag/js/gpt.js">
                                    googletag.pubads().enableSyncRendering();
                                    googletag.enableServices();
                                    googletag.pubads().display(\'/25379366/Passback\', [' . $size->getWidth() . ', ' . $size->getHeight() . '], \'div-gpt-ad-${REQUESTID}-0\',\'${CLICKURL}\');
                                    </script>';

                $this->entity_passback->add($this->token, $passback);
            }

            return array(
                'publisher_key' => $publisherId
            );
        } catch (Exception $ex) {
            $this->_manageException('New Publisher ' . $publisher->getName() . '_' . $publisher->user->getId() . ': ' . $ex->getMessage());
        }
    }

    public function assignPaymentRule($param) {
        return TRUE;
    }

    public function assignMediaBuyer(Publisher $publisher) {
        try {
            $this->entityInsertionOrder();
            $sellerId = $publisher->getAdserverKey($this->adserver->getId());
            $insertionOrder = $this->entity_insertion_order->getBySeller($this->token, $sellerId, 1, 1);
            $insertion_order = new stdClass();
            $insertion_order->id = $insertionOrder['insertion_orders'][0]->id;
            $insertion_order->buyer_contact_id = $publisher->mediaBuyer->getAdserverKey($this->adserver->getId());
            $this->entity_insertion_order->update($this->token, $insertion_order);
        } catch (Exception $ex) {
            $this->_manageException('Assign media buyer to ' . $publisher->getName() . ': ' . $ex->getMessage());
        }
    }

    public function newSite(Site $site) {
        try {
            $this->entitySite();
            $data_site = new stdClass();
            $data_site->site = new stdClass();

            $data_site->site->description = $site->getName();
            $data_site->site->publisher_entity_id = $site->publisher->getAdserverKey($this->adserver->getId());
            $data_site->site->active = true;
            $data_site->site->site_url = 'http://' . $site->getName();
            return $this->entity_site->add($this->token, $data_site->site);
        } catch (Exception $ex) {
            $this->_manageException('New Site ' . $site->getName() . ': ' . $ex->getMessage());
            if ($ex->getMessage() == 'One or more domains associated with this site_url are either banned or non-transparent.') {
                throw new Exception('banned');
            }
        }
    }

    public function newSection(Section $section) {
        try {
            $this->entitySection();

            $data_section = new stdClass();
            $data_section->section = new stdClass();
            $url = new stdClass();
            if ($section->site->getDomainList() == NULL) {
                $url->url = 'http://' . $section->getName();
                $urls = array($url);
            } else {
                $domains = explode("\n", $section->site->getDomainList());
                $urlArray = array();
                foreach ($domains as &$domain) {
                    $url = new stdClass();
                    $url->url = 'http://' . trim($domain);
                    $urlArray[] = $url;
                }
                $urls = $urlArray;
            }
            $data_section->section->description = $section->getName();
            $data_section->section->site_id = $section->site->getAdserverKey($this->adserver->getId());
            $data_section->section->registered_urls = $urls;
            $data_section->section->active = true;
            $data_section->section->channels = $section->site->getArrayOfAdserverCategories($this->adserver->getId());
            return $this->entity_section->add($this->token, $data_section->section);
        } catch (Exception $ex) {
            $this->_manageException('New Section ' . $section->getName() . ': ' . $ex->getMessage());
        }
    }

    public function getAdserverPlacementName($placement) {
        return $placement->getName();
    }

    public function categorizeSite(Site $site) {
        try {
            $section = Section::getSectionBySite($site->getId());

            $this->entitySection();

            $data_section = new stdClass();
            $data_section->section = new stdClass();

            $data_section->section->id = $section->getAdserverKey();
            $data_section->section->channels = $section->site->getArrayOfAdserverCategories($this->adserver->getId());
            $this->entity_section->update($this->token, $data_section->section);
        } catch (Exception $ex) {
            $this->_manageException('Categorize Section of Site ' . $site->getName() . ': ' . $ex->getMessage());
        }
    }

    public function categorizeSites($sites) {
        try {
            foreach ($sites as $siteid) {
                $section = Section::getSectionBySite($siteid->sit_id);
                if($section){
                $site = Site::find($siteid->sit_id);
                $this->entitySection();

                $data_section = new stdClass();
                $data_section->section = new stdClass();

                $data_section->section->id = $section->getAdserverKey();
                $data_section->section->channels = $section->site->getArrayOfAdserverCategories($this->adserver->getId());
                $this->entity_section->update($this->token, $data_section->section);
                Site::updateSiteSetCategorizedFTrue($siteid->sit_id);
                echo "\tcategorizado: ".$site->getName()." - ".$site->getId()."\n";
                sleep(2);
                }
            }
        } catch (Exception $ex) {
            $this->_manageException('Categorize Section of Sites: ' . $ex->getMessage());
        }
    }

    public function getDefaultSectionKeyBySite($siteKey) {
        try {
            $this->entitySection();
            $result = $this->entity_section->getBySite($this->token, $siteKey);
            return $result['sections'][0]->id;
        } catch (Exception $ex) {
            $this->_manageException('Get Default Section of site ' . $siteKey . ': ' . $ex->getMessage());
        }
    }

    public function getReport($data) {
        try {
            self::entityReport();

            $url = NULL;

            $data['publisher_id'] = Publisher::find($data['publisher_id']);

            if (!$data['publisher_id'])
                return NULL;

            $data['publisher_id'] = $data['publisher_id']->getAdserverKey(1);

            $data['groups'] = $this->getGroup($data['group_by']);

            $data['columns'] = $this->getColumns($data['columns']);

            $data['time'] = 'today';

            $xml = self::getXMLRequestPublisherReport($data);

            for ($i = 0; $i < 60; $i++) {
                if ($url = self::$entity_report->status($this->token, self::getTokenReport($xml)))
                    break;
                sleep(2);
            }

            if (!$url)
                die('MAX_ATTEMPTS_EXCEEDED');

            $xml = simplexml_load_string(self::getCURL($url));

            $json = json_encode($xml);
            $array = json_decode($json, TRUE);

            return $this->processReport($array);
        } catch (Exception $ex) {
            $resp[0]=NULL;
            return $resp;
        }
    }

    /*     * *
     * Private functions
     */

    private function getGroup($data) {

        $adserverGroups = array(
            // sitio - anuncio
            'site_adserver_id' => 'site_id',
            // anuncio
            'placement_adserver_id' => 'section_id',
            // pais
            'country_name' => 'country_woe_id',
            // sitio
            'site_adserver_id' => 'site_id',
            // tamano
            'size_adserver_id' => 'size_id',
            // dia
            'day' => 'time',
            // mes
            'month' => 'time',
            // publisher
            'publisher_adserver_id' => 'publisher_id'
        );

        foreach ($data as $group) {
            if (isset($adserverGroups[$group]))
                $groups[] = $adserverGroups[$group];
        }

        return $groups;
    }

    private function processReport($datos) {
        $header = $datos['RESPONSE']['DATA']['HEADER'];

        $data_report = FALSE;
        if (isset($datos['RESPONSE']['DATA']['ROW']))
            $data_report = $datos['RESPONSE']['DATA']['ROW'];

        $columns = $this->setColumns($header["COLUMN"]);

        $total_report = sizeof($data_report);

        if ($data_report) {
            foreach ($data_report as $row) {

                $dat = new stdClass();

                if ($total_report > 1)
                    $row = $row["COLUMN"];

                $a = 0;
                foreach ($row as $value) {
                    if ($columns[$a] === 'day') {
                        $dat->$columns[$a] = date('Y-m-d', strtotime($value));
                    } else {
                        $dat->$columns[$a] = $value;
                    }
                    $a++;
                }

                $dat->ctr = ($dat->clicks / $dat->imps) * 100;
                $dat->cpm = ($dat->revenue / $dat->imps) * 1000;

                $results[] = $dat;
            }

            return $results;
        }

        return FALSE;
    }

    private function getColumns($setColumns) {

        $adserverColumns = array(
            'day' => 'time',
            'month' => 'time',
            'publisher_name' => 'publisher_name',
            'site_name' => 'site_name',
            'placement_name' => 'size_name',
            'country_name' => 'country_woe_name',
        );

        foreach ($setColumns as $column) {
            if (isset($adserverColumns[$column]))
                $columns[] = $adserverColumns[$column];
        }

        $columns[] = 'imps';
        $columns[] = 'clicks';
        //$columns[] = 'ctr';
        //$columns[] = 'cpm';
        $columns[] = 'net_pub_comp';

        return $columns;
    }

    private function setColumns($setColumns) {
        $adserverColumns = array(
            'time' => 'day',
            'publisher_name' => 'publisher_name',
            'site_name' => 'site_name',
            'size_name' => 'placement_name',
            'country_woe_name' => 'country_name',
        );

        foreach ($setColumns as $column) {
            if (isset($adserverColumns[$column]))
                $columns[] = $adserverColumns[$column];
        }

        $columns[] = 'imps';
        $columns[] = 'clicks';
        //$columns[] = 'ctr';
        //$columns[] = 'cpm';
        $columns[] = 'revenue';

        return $columns;
    }

    private function getTokenReport($xml) {
        try {
            $token_report = self::$entity_report->requestViaXML($this->token, $xml);

            if (!$token_report) {
                $token_report = NULL;
                die('REPORT_TOKEN_NULL');
            }

            return $token_report;
        } catch (Exception $ex) {
            $this->_manageException('Get token for report: ' . $ex->getMessage());
        }
    }

    private static function getXMLRequestPublisherReport($data) {
        $xml = '<?xml version="1.0"?>';
        $xml .= '<RWRequest>';
        $xml .= '<REQUEST domain="publisher" service="ComplexReport" entity="' . $data['publisher_id'] . '" filter_entity_id="' . $data['publisher_id'] . '" time_zone="EST">';

        $xml .= '<ROWS>';

        $priority = 0;
        foreach ($data['groups'] as $value) {
            $priority++;
            if ($value == 'time')
                $xml .= '<ROW type="group" priority="' . $priority . '" ref="' . $value . '" interval="day" includeascolumn="n" suppressoutput="y"/>';
            else
                $xml .= '<ROW type="group" priority="' . $priority . '" ref="' . $value . '" includeascolumn="n" suppressoutput="y"/>';
        }

        $priority++;

        $xml .= '<ROW type="group" priority="' . $priority . '" ref="time" interval="day" includeascolumn="n"/>';

        $xml .= '</ROWS>';

        $xml .= '<COLUMNS>';

        foreach ($data['columns'] as $value)
            $xml .= '<COLUMN ref="' . $value . '"/>';

        $xml .= '</COLUMNS>';
        $xml .= '<FILTERS>';

        if ($data['time'] == 'custom') {
            $xml .= '<FILTER ref="time" start="' . $data['start_date'] . '" end="' . $data['end_date'] . '" />';
        } else {
            $xml .= '<FILTER ref="time" macro="' . $data['time'] . '"/>';
        }

        $xml .= '</FILTERS>';
        $xml .= '</REQUEST>';
        $xml .= '</RWRequest>';

        return $xml;
    }

    private function getCURL($url) {
        try {
            $curl_object = curl_init();
            curl_setopt($curl_object, CURLOPT_URL, $url);
            curl_setopt($curl_object, CURLOPT_HEADER, 0);
            curl_setopt($curl_object, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($curl_object, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_object, CURLOPT_SSL_VERIFYHOST, 0);

            $result = curl_exec($curl_object);
            $error = curl_error($curl_object);
            $info = curl_getinfo($curl_object);
            curl_close($curl_object);

            if ($error)
                die('CURL_ERROR');

            return $result;
        } catch (Exception $ex) {
            $this->_manageException('Get CURL: ' . $ex->getMessage());
        }
    }

    private function getAdserver() {
        return Adserver::where('adv_class_name', get_class($this))->first();
    }

    private function _manageException($ex) {
        // open log file
        $filename = public_path() . "/logs/yax.log";
        $fh = fopen($filename, "a") or die();
        fwrite($fh, date("d-m-Y, H:i") . " - $ex\n") or die();
        fclose($fh);
    }

}
