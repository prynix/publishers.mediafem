<?php

class Utility {

    /**
     * Desencripta los permisos de la sesion y 
     * @param string $tab
     * @return boolean
     */
    public static function hasPermission($tab) {
        $encrypted = Session::get('permissions');
        if ($encrypted) {
            $permissions = Utility::decryptArray($encrypted);
            foreach ($permissions as $permission) {
                if (preg_match("/^" . $tab . "/", $permission)) {
                    return TRUE;
                }
            }
            return FALSE;
        } else {
            return FALSE;
        }
    }

    /**
     * Encripta cada valor del array.
     * @param array $array
     * @return array
     */
    public static function encryptArray($array) {
        $encrypted = array();
        foreach ($array as $value) {
            $encrypted[] = Crypt::encrypt($value);
        }
        return $encrypted;
    }

    /**
     * Desencripta cada valor del array.
     * @param array $array
     * @return array
     */
    public static function decryptArray($array) {
        $decrypted = array();
        foreach ($array as $value) {
            $decrypted[] = Crypt::decrypt($value);
        }
        return $decrypted;
    }

}
