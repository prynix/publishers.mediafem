<?php

use LaravelBook\Ardent\Ardent;

class AdminEarning extends Ardent {

    protected $fillable = array('admern_amount', 'admern_period');
    protected $guarded = array();
    protected $primaryKey = 'admern_id';
    public static $rules = array(
        'admern_amount' => 'required|numeric',
        'admern_period' => 'required',
    );

    /*     * *
     * Setters
     */

    public function setAdministrator($adminId) {
        $this->administrator()->associate(Administrator::find($adminId));
    }

    public function setPeriod($date) {
        $this->admern_period = $date;
    }

    public function setAmount($amount) {
        $this->admern_amount = $amount;
    }

    public function setDescription($description) {
        $this->admern_description = $description;
    }

    /*     * *
     * Getters
     */

    public function getPublisherName() {
        return $this->publisher->pbl_name;
    }

    public function getPeriod() {
        return $this->admern_period;
    }

    public function getAmount() {
        return $this->admern_amount;
    }

    public function getConcept() {
        if ($this->admern_description == NULL)
            return dateToStringMonthYear($this->getPeriod());
        else
            return $this->admern_description.' - '.dateToStringMonthYear($this->getPeriod());
    }

    public function getMonth() {
        return date("m", strtotime($this->admern_period));
    }

    public function getDescription() {
        return $this->admern_description;
    }

    public function billed() {
        if (count($this->billing) > 0)
            return true;
        return false;
    }

    /*     * *
     * Relationships
     */

    public function administrator() {
        return $this->belongsTo('Administrator', 'admern_administrator_id');
    }

    public function billing() {
        return $this->belongsToMany('AdminBilling', 'admin_billing_admin_earning', 'earning_id', 'billing_id');
    }

    public static function greaterThanZero() {
        $earnings = self::all();
        foreach ($earnings as $key => $row) { //2
            if (number_format($row->getAmount(), 2) == 0.00) {
                unset($earnings[$key]);  //3
            }
        }
        return $earnings;
    }

}
