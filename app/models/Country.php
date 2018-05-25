<?php

use LaravelBook\Ardent\Ardent;

class Country extends Ardent {

    protected $primaryKey = 'cnt_id';
    public $forceEntityHydrationFromInput = false;
    public $autoHydrateEntityFromInput = false;
    protected $fillable = array('cnt_id');
    protected $guarded = array();
    public static $rules = array();

    public function currency() {
        return $this->belongsTo('Currency', 'cnt_currency_id');
    }

    public function adservers() {
        return $this->belongsToMany('Adserver')->withPivot('adv_cnt_adserver_key');
    }

    /*     * *
     * Devuelve el id del pais en $idAdserver adserver
     * En caso de no estar registrado devuelve null
     */

    public function getAdserverKey($idAdserver) {
        foreach ($this->adservers as $key) {
            if ($key->pivot->adserver_id == $idAdserver)
                return $key->pivot->adv_cnt_adserver_key;
        }
        return null;
    }
    
    public static function getCountryId($key, $adserverId) {
        $country = DB::table('adserver_country')
                ->where('adserver_id', $adserverId)
                ->where('adv_cnt_adserver_key', $key)
                ->limit(1)
                ->get();
        if (count($country)) {
            return $country[0]->country_id;
        } else {
            return NULL;
        }
    }

}