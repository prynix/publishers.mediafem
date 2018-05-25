<?php

use LaravelBook\Ardent\Ardent;

class Earning extends Ardent {

    protected $fillable = array('ern_amount', 'ern_period');
    protected $guarded = array();
    protected $primaryKey = 'ern_id';
    public static $rules = array(
        'ern_amount' => 'required|numeric',
        'ern_period' => 'required',
    );

    /*     * *
     * Setters
     */

    public function setPublisher($publisherId) {
        $this->publisher()->associate(Publisher::find($publisherId));
    }

    public function setPeriod($date) {
        $this->ern_period = $date;
    }

    public function setAmount($amount) {
        $this->ern_amount = $amount;
    }

    public function setDescription($description) {
        $this->ern_description = $description;
    }

    /*     * *
     * Getters
     */

    public function getPublisherName() {
        return $this->publisher->pbl_name;
    }

    public function getPeriod() {
        return $this->ern_period;
    }

    public function getAmount() {
        return $this->ern_amount;
    }

    public function getConcept() {
        if ($this->ern_description == NULL)
            return dateToStringMonthYear($this->getPeriod());
        else
            return $this->ern_description.' - '.dateToStringMonthYear($this->getPeriod());
    }

    public function getMonth() {
        return date("m", strtotime($this->ern_period));
    }

    public function getDescription() {
        return $this->ern_description;
    }

    public function billed() {
        if (count($this->billing) > 0)
            return true;
        return false;
    }

    /*     * *
     * Relationships
     */

    public function publisher() {
        return $this->belongsTo('Publisher', 'ern_publisher_id');
    }

    public function billing() {
        return $this->belongsToMany('Billing');
    }

    public static function greaterThanZero() {
        $earnings = Earning::all();
        foreach ($earnings as $key => $row) { //2
            if (number_format($row->getAmount(), 2) == 0.00) {
                unset($earnings[$key]);  //3
            }
        }
        return $earnings;
    }

}
