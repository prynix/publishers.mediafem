<?php

class ImonomyApi {

    const USER = 'adtomatik';
    const PASSWORD = 'Adtomatik15';
    const NETWORK = 8;

    public function getReport($publisher_adserver_key, $site_adserver_key) {
        $filter = new stdClass();
        $filter->network = self::NETWORK;
        $filter->publisher = $publisher_adserver_key;
        $filter->website = $site_adserver_key;
        $filter->timePeriodType = 'last';
        $filter->timePeriod = 'yesterday';
        //$filter->starttime = '';
        //$filter->endtime = '';
        $filter->displayByDate = 'True';
        $filter->displayByPlacement = 'False';
        $filter->displayByCountry = 'True';
        $filter->displayByWebsite = 'True';
        $filter->displayByPublisher = 'True';
        $filter->displayByNetwork = 'False';
        $filter->export = 'csv';
        $filter->username = self::USER;
        $filter->password = self::PASSWORD;


        $request = new ApiRequest();
        $request->setMethod('post');
        $request->setUri('http://dashboard.imonomy.com/api/stat/network/' . self::NETWORK);
        $request->setData($filter);
        $request->setEncodeData('array');
        $request->setSaveResponse(TRUE);
        $request->setIsImonomy(TRUE);
        $call = new Caller();
        try {
            $res = $call->call($request);
        } catch (Exception $ex) {
            echo $ex->getMessage() . "\n";
            $this->_manageException('Imonomy ' . $ex->getMessage());
            $res = "";
        }
        $rows = explode("\n", $res);
        //Quita primer elemento
        array_shift($rows);
        //Quita dos ultimos elementos (linea vacia y los totales)
        array_pop($rows);
        array_pop($rows);
        return $rows;
    }

    private function _manageException($ex) {
        // open log file
        $filename = public_path() . "/logs/imonomy.log";
        $fh = fopen($filename, "a") or die();
        fwrite($fh, date("d-m-Y, H:i") . " - $ex\n") or die();
        fclose($fh);
    }

}
