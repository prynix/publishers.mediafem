<?php

class Tabs extends Illuminate\Html\FormBuilder {
    
    public static function permission($tab = '', $tabHTML = ''){
        if($tab != ''){
            if(Utility::hasPermission($tab)){
                return $tabHTML;
            }
        }
        return '';
    }
}