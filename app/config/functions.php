<?php

/*
 * BORRAR
 */

function getNumberWithDecimal($number) {

    if (Session::get('lang') == "ES")
        return number_format($number, 3, ',', '.');
    return number_format($number, 3, '.', ',');
}

function getNumberWithoutDecimal($number) {

    if (Session::get('lang') == "ES")
        return number_format($number, 0, ',', '.');
    return number_format($number, 0, '.', ',');
}

/* * *
 * - quita espacios, pasa todo a minuscula, obtiene el dominio con las
 * paths ('/...'), quita 'www.' y elimina las '/' del final.
 */

function cleanUrl($url) {
    $url = trim(strtolower($url));
    $url = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH));
    while ($url[strlen($url) - 1] == "/") {
        $url = substr($url, 0, -1);
    }
    return $url;
}

function validate_domain_regular_expression($domain) {
    return preg_match('(^(?:[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9])*\.)+[a-zA-Z]{2,6}$)', $domain);
}

/*
 * Devuelve fecha desde y fecha hasta en formato Y-m-d 00:00:00 a partir de un intervalo
 *
 */

function getDatetimeByInterval($interval) {
    if ($interval == "today") {
        $start_date = date('Y-m-d 00:00:00');
        $end_date = date('Y-m-d 23:59:59');
    } elseif ($interval == "yesterday") {
        $start_date = date('Y-m-d 00:00:00', strtotime("-1 day"));
        $end_date = date('Y-m-d 23:59:59', strtotime("-1 day"));
    } elseif ($interval == "last_7_days") {
        $start_date = date('Y-m-d 00:00:00', strtotime("-7 days"));
        $end_date = date('Y-m-d 23:59:59');
    } elseif ($interval == "month_to_date") {
        $start_date = date('Y-m-d 00:00:00', strtotime('this month', strtotime(date('Y-m-01'))));
        $end_date = date('Y-m-d 23:59:59');
    } elseif ($interval == "last_month") {
        $start_date = date('Y-m-d 00:00:00', strtotime('-1 month', strtotime(date('Y-m-01'))));
        $end_date = date('Y-m-d 23:59:59', strtotime("-" . Date("d") . " days"));
    } else {
        $interval = explode('-to-', $interval);
        $start_date = date('Y-m-d 00:00:00', strtotime($interval[0]));
        $end_date = date('Y-m-d 23:59:59', strtotime($interval[1]));
    }
    
    return array('start_date' => $start_date, 'end_date' => $end_date);
}

/* * *
 * return string format date "Month - year"
 */

function dateToStringMonthYear($date) {
    $str = '';
    $str = Lang::get('meses.' . date("m", strtotime($date))) . ' - ' . date("Y", strtotime($date));
    return $str;
}

/* * *
 * increment days to date
 * return new date
 */

function incremetDaysToDate($days, $date) {
    $date = strtotime("+$days day", strtotime($date));
    return date("Y-m-d", $date);
}

function incremetMonthsToDate($days, $date) {
    $date = strtotime("+".floor($days/30)." month", strtotime($date));
    return date("Y-m-d", $date);
}

/* * *
 * Set uppercase string first letter
 */

function uppercaseFirstLetter($text) {
    return ucfirst($text);
}

/**
 * Search in multidimensional array
 * @param ToSearch $needle
 * @param Array $haystack
 * @param String $property
 * @return boolean
 */
function multidimensional_array_search($needle,$haystack, $property) {
    foreach($haystack as $value) {
        if($needle == $value->$property) {
            return $value;
        }
    }
    return false;
}

/***
 * Custom Crypt
 */
function encrypt($id)
{
    $key = md5('adtomatik_media_buyer', true);
    $id = base_convert($id, 10, 36); // Save some space
    $data = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $id, 'ecb');
    $data = bin2hex($data);

    return $data;
}

function decrypt($encrypted_id)
{
    $key = md5('adtomatik_media_buyer', true);
    $data = pack('H*', $encrypted_id); // Translate back to binary
    $data = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $data, 'ecb');
    $data = base_convert($data, 36, 10);

    return $data;
}