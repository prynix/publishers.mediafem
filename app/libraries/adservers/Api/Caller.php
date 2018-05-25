<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Caller
 *
 * @author Valeria
 */
class Caller {

    private $tries = 5;

    public function call(ApiRequest $request, $isImonomy = FALSE) {

        $request->validate();
        $curlHandle = curl_init($request->uri);

        switch ($request->method) {
            case 'put':
                if ($request->ecodeData == "json") {
                    $dataString = json_encode($request->data);
                } elseif ($request->ecodeData == "array") {
                    $dataString = get_object_vars($request->data);
                }
                $fh = fopen("php://temp", "w");
                fwrite($fh, $dataString);
                rewind($fh);
                curl_setopt($curlHandle, CURLOPT_PUT, true);
                curl_setopt($curlHandle, CURLOPT_INFILE, $fh);
                curl_setopt($curlHandle, CURLOPT_INFILESIZE, strlen($dataString));
                break;

            case 'post':
                curl_setopt($curlHandle, CURLOPT_POST, 1);
                if ($request->ecodeData == "json") {
                    $dataString = json_encode($request->data);
                } elseif ($request->ecodeData == "array") {
                    $dataString = get_object_vars($request->data);
                }
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $dataString);
                break;

            case 'delete':
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode($request->data));
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;

            default:
                break;
        }
        
        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        if ($request->isImonomy) {
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 0);
        } else {
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        }

        // Set Token if is needed or set Imonomy required headers
        if (!empty($request->token)) {
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Authorization: ' . $request->token));
        } elseif ($request->isImonomy) {
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
                'POST /api/stat/network/8 HTTP/1.1',
                'Host: dashboard.imonomy.com',
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:37.0) Gecko/20100101 Firefox/37.0',
                'Referer: http://localhost/imohtml.html',
                'Cookie: _ga=GA1.2.1675973364.1429215771',
                'Connection: keep-alive',
            ));
        }

        if ($request->saveResponse) {
            ob_start();
            // Excecute Request
            curl_exec($curlHandle);
            $rs = ob_get_contents();
            ob_end_clean();
        } else {
            // Excecute Request
            $rs = curl_exec($curlHandle);

            // Decode response if is needed
            if ($request->decodeResponse)
                $this->_decodeResponse($rs, $request);
        }
        /*
        $con2 = mysql_connect("205.186.153.231", "produccion", "prod_2013");
        mysql_select_db("produccion_mediafem", $con2);
        
        mysql_query("INSERT INTO requests (uri, aplicacion, method, ip, response) VALUES ('$request->uri', 'adt', '$request->method','" .
           $_SERVER['REMOTE_ADDR'] . "', '$rs');");
               */
        
        curl_close($curlHandle);
        return $rs;
    }

    public function downloadReport(ApiRequest $request) {
        $request->validate();

        $method = strtolower($request->method);
        $curlHandle = curl_init($request->uri);

        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

        if (!empty($request->token))
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Authorization: ' . $request->token));

        $rs = curl_exec($curlHandle);

        return $rs;
    }

    private function _decodeResponse(&$rs, $request) {
        $rs = json_decode($rs);

        if (isset($rs->response->error)) {
            if ($rs->response->error_code == "RATE_EXCEEDED" || $rs->response->error_id == "LIMIT") {
                sleep(2);
                if ($this->tries > 0) {
                    $this->tries -= 1;
                    self::call($request);
                } else
                    throw new Exception('After 5 tries. ' . $rs->response->error_id . '::' . $rs->response->error);
            }
            throw new Exception($rs->response->error_id . '::' . $rs->response->error);
        } else if (!(isset($rs->response->status) && $rs->response->status == 'OK')) {
            return 'Request failed';
        }

        return $rs;
    }

}
