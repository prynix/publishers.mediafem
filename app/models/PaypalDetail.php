<?php

class PaypalDetail extends Eloquent {

    public $autoHydrateEntityFromInput = true;
    public $forceEntityHydrationFromInput = true;
    protected $table = 'paypal_details';
    protected $primaryKey = 'ppl_id';
    protected $fillable = array('ppl_publisher_id', 'ppl_email');
    protected $guarded = array();
    public static $rules = array();

    public function publisher() {
        return $this->belongsTo('Publisher', 'ppl_publisher_id');
    }

    public function administrator() {
        return $this->belongsTo('Administrator', 'ppl_administrator_id');
    }

    /*
     * GETs
     */

    public static function findByPublisherID($publisher_id) {
        return self::where('ppl_publisher_id', $publisher_id)->first();
    }

    public static function findByAdministratorID($administrator_id) {
        return self::where('ppl_administrator_id', $administrator_id)->first();
    }

    public function setAdministrator($id) {
        $this->administrator()->associate(Administrator::find($id));
    }
}
