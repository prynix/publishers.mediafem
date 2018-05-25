<?php

// app/validators/customValidation.php
class customValidation {

    public function curl_url($field, $value, $parameters) {
        return true;
        /* $nombre = trim(strtolower($value));
          while ( $nombre[strlen($nombre)-1] == "/" ) {
          $nombre = substr($nombre, 0, -1);
          }
          $nombre = str_ireplace(parse_url($nombre, PHP_URL_SCHEME).'://', '', $nombre); */

        $url = 'http://www.' . $value;
        
        $response = true;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);
        if ($result !== false) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 404) {
                $response = false;
            } else {
                if (preg_match('/^http:\/\/[a-z0-9-]{1,}?\.?[a-z0-9-]*\.?[a-z0-9]{3}?.[a-z]{2,}(\/[a-z0-9-])?\/?$/i', $url)) {
                    $response = true;
                } else {
                    $response = false;
                }
            }
        } else {
            $response = false;
        }

        if ($response)
            return true;
        return false;
    }

}

