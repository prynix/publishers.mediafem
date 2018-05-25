<?php

use LaravelBook\Ardent\Ardent;

class Profile extends Ardent {

    public $autoHydrateEntityFromInput = true;
    public $forceEntityHydrationFromInput = true;
    protected $primaryKey = 'prf_id';
    protected $guarded = array();
    protected $fillable = array('prf_name', 'prf_birthday', 'prf_city', 'prf_address', 'prf_zip_code', 'prf_phone_number');

    /*
     * BEFOREs
     */



    /*
     * LINKs
     */

    public function user() {
        return $this->belongsTo('User', 'prf_user_id');
    }

    public function country() {
        return $this->belongsTo('Country', 'prf_country_id');
    }

    public function language() {
        return $this->belongsTo('Language', 'prf_language_id');
    }

    /*
     * GETs
     */

    public static function findByUserID($user_id) {
        return self::where('prf_user_id', $user_id)->first();
    }
    
    public function getName() {
        return $this->prf_name;
    }
    
    public function getCity() {
        return $this->prf_city;
    }
    
    public function getAddress() {
        return $this->prf_address;
    }
    
    public function getZipCode() {
        return $this->prf_zip_code;
    }

    /*
     * SETs
     */

    public function setName($name) {
        $this->prf_name = $name;
    }
    
    public function setUser($id) {
        $this->user()->associate(User::find($id));
    }

    public function setCountry($id) {
        $this->country()->associate(Country::find($id));
    }

    public function setLanguage($short) {
        $this->language()->associate(Language::getLanguageByShort($short));
    }

    /*
     * OTHERs
     */

    public static $rules = array(
        'prf_name' => 'required|between:5,50',
        'prf_country_id' => 'required',
        'prf_user_id' => 'required',
        'prf_language_id' => 'required',
        'prf_city' => 'required|between:5,50',
        'prf_address' => 'required|between:5,50',
        'prf_zip_code' => 'required'
    );

    public static function completeData() {
        $dataComplete = DB::table('profiles')
                ->where('prf_user_id', Session::get('user.id'))
                ->where('prf_name', '!=', '')
                ->where('prf_country_id', '!=', '')
                ->where('prf_city', '!=', '')
                ->where('prf_address', '!=', '')
                ->where('prf_zip_code', '!=', '')
                ->count();
        
        if ($dataComplete <= 0)
            return FALSE;

        return TRUE;
    }

}