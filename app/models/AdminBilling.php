<?php

use LaravelBook\Ardent\Ardent;

class AdminBilling extends Ardent {

    protected $guarded = array();
    protected $primaryKey = 'admbll_id';
    public static $rules = array();

    /*     * *
     * Setters
     */

    public function setStipulatedDate($date) {
        $this->admbll_stipulated_date = $date;
    }

    public function setBalance($balance) {
        $this->admbll_balance = $balance;
    }

    /*     * *
     * Getters
     */
    public function getId() {
        return $this->admbll_id;
    }
    
    public function getConcept() {
        $earnings = $this->earnings;
        
        if ($earnings[0]->ern_description == NULL)
            return $this->getStringOfRange();
        else
            return $earnings[0]->getConcept();
    }

    public function getBalance() {
        return $this->admbll_balance;
    }

    public function getStipulatedDate() {
        return $this->admbll_stipulated_date;
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

    public function getAdministrator() {
        return (count($this->earnings) > 0) ? $this->earnings[0]->administrator : null;
    }

    /*     * *
     * Relationships
     */

    public function payments() {
        return $this->hasMany('Payment', 'admpym_billing_id');
    }

    public function earnings() {
        return $this->belongsToMany('AdminEarning', 'admin_billing_admin_earning', 'billing_id', 'earning_id');
    }

    /*     * *
     * Others
     */

    public function discountBalance($amount) {
        $this->admbll_balance -= $amount;
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

    public static function ofAdministrator($adminId) {
        $allBillings = self::orderBy('admbll_id', 'ASC')->get();
        $billingOfAdministrator = array();
        foreach ($allBillings as $billing) {
            if ($billing->getAdministrator()) {
                if ($billing->getAdministrator()->getId() == $adminId && $billing->getBalance() > 0)
                    $billingOfAdministrator[] = $billing;
            }
        }
        return $billingOfAdministrator;
    }

    public static function didNotPaid() {        
        return self::where('admbll_balance', '>', 0)->get();
    }

}
