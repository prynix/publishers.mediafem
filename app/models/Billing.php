<?php

use LaravelBook\Ardent\Ardent;

class Billing extends Ardent {

    protected $guarded = array();
    protected $primaryKey = 'bll_id';
    public static $rules = array();

    /*     * *
     * Setters
     */

    public function setStipulatedDate($date) {
        $this->bll_stipulated_date = $date;
    }

    public function setBalance($balance) {
        $this->bll_balance = $balance;
    }

    /*     * *
     * Getters
     */

    public function getId() {
        return $this->bll_id;
    }
    
    public function getConcept() {
        $earnings = $this->earnings;
        
        if ($earnings[0]->ern_description == NULL)
            return $this->getStringOfRange();
        else
            return $earnings[0]->getConcept();
    }

    public function getBalance() {
        return $this->bll_balance;
    }

    public function getStipulatedDate() {
        return $this->bll_stipulated_date;
    }

    public function getLastDate() {
        $count = count($this->earnings);
        $array_earnings = array();
        foreach ($this->earnings as $earning) {
            $array_earnings[] = $earning;
        }
        return $array_earnings[$count - 1]->getPeriod();
    }

    /*     * *
     * Devuelve el publisher del primer earning asignado
     * (suponindo que todos los earnings asignados son del mismo publisher)
     */

    public function getPublisher() {
        return (count($this->earnings) > 0) ? $this->earnings[0]->publisher : null;
    }

    /*     * *
     * Relationships
     */

    public function payments() {
        return $this->hasMany('Payment', 'pym_billing_id');
    }

    public function earnings() {
        return $this->belongsToMany('Earning');
    }

    /*     * *
     * Others
     */

    public function discountBalance($amount) {
        $this->bll_balance -= $amount;
    }

    /*     * *
     * exampe of return: "desde Diciembre - 2012 hasta Agosto - 2014"
     */

    private function getStringOfRange() {
        $array_earnings = array();
        foreach ($this->earnings as $earning) {
            $array_earnings[] = $earning;
        }
        if (count($array_earnings) > 0) {
            if (count($array_earnings) > 1) {
                usort($array_earnings, array($this, 'date_compare'));
                return Lang::get('payments.from') . ' ' . $array_earnings[0]->getConcept()
                        . ' ' . Lang::get('payments.to') . ' ' . end($array_earnings)->getConcept();
            } else
                return Lang::get('payments.of') . ' ' . $array_earnings[0]->getConcept();
        }
        return '';
    }

    private function date_compare($a, $b) {
        $t1 = strtotime($a->getPeriod());
        $t2 = strtotime($b->getPeriod());
        return $t1 - $t2;
    }

    public static function ofPublisher($publisherId) {
        $allBillings = self::orderBy('bll_id', 'ASC')->get();
        $billingOfPublisher = array();
        foreach ($allBillings as $billing) {
            if ($billing->getPublisher()) {
                if ($billing->getPublisher()->getId() == $publisherId && $billing->getBalance() > 0)
                    $billingOfPublisher[] = $billing;
            }
        }
        return $billingOfPublisher;
    }

    public static function didNotPaid() {        
        return self::where('bll_balance', '>', 0)->get();
    }

}
