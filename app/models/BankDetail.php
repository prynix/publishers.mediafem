<?php

class BankDetail extends Eloquent {

    public $autoHydrateEntityFromInput = true;
    public $forceEntityHydrationFromInput = true;

    protected $table = 'bank_details';
    protected $primaryKey = 'bnk_id';
    protected $fillable = array('bnk_publisher_id', 'bnk_account_name', 'bnk_account_number', 'bnk_bank_name', 'bnk_city', 'bnk_bic_code', 'bnk_intermediary_bank', 'bnk_cbu', 'bnk_cuit', 'bnk_route_code');
    protected $guarded = array();
    public static $rules = array();

    public function publisher() {
        return $this->belongsTo('Publisher', 'bnk_publisher_id');
    }

    public function administrator() {
        return $this->belongsTo('Administrator', 'bnk_administrator_id');
    }

    public function country() {
        return $this->belongsTo('Country', 'bnk_country_id');
    }

    /*
     * GETs
     */

    public static function findByPublisherID($publisher_id) {
        return self::where('bnk_publisher_id', $publisher_id)->first();
    }

    public static function findByAdministratorID($administrator_id) {
        return self::where('bnk_administrator_id', $administrator_id)->first();
    }

    /*
     * SETs
     */
    public function setCountry($id) {
        $this->country()->associate(Country::find($id));
    }
    public function setAdministrator($id) {
        $this->administrator()->associate(Administrator::find($id));
    }

}