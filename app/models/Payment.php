<?php

use LaravelBook\Ardent\Ardent;

class Payment extends Ardent {

    protected $fillable = array('pym_amount', 'pym_description');
    protected $guarded = array();
    protected $primaryKey = 'pym_id';
    public static $rules = array(
        'pym_billing_id' => 'required',
        'pym_amount' => 'required|numeric|min:0.001',
        'pym_description' => 'required'
    );

    /*     * *
     * para obtener la descripcion:
     * * * Lang::get('payments.'.$this->pym_description);
     */
    public static $typeOfPayments = array('total_payment', 'partial_payment', 'adjustments');

    /*     * *
     * Setters
     */

    public function setAmount($amount) {
        $this->pym_amount = $amount;
    }

    public function setDescription($description) {
        $this->pym_description = $description;
    }

    public function setDate($date) {
        $this->created_at = date('Y-m-d H:i:s', strtotime($date));
    }

    public function setBilling($billingId) {
        $this->billing()->associate(Billing::find($billingId));
    }
    
    public function revertPayment() {
        $this->billing->setBalance($this->getAmount());
        $this->billing->forceSave();
        $this->delete();
    }

    /*     * *
     * Getters
     */

    public static function getPayment() {
        return DB::table('payments')->where('YEAR(created_at)', '=', '2015');
    }

    public function getId() {
        return $this->pym_id;
    }

    public function getAmount() {
        return $this->pym_amount;
    }

    public function getConcept() {
        if (($this->pym_description == 'total_payment') || ($this->pym_description == 'partial_payment') || ($this->pym_description == 'adjustments'))
            return uppercaseFirstLetter(Lang::get('payments.' . $this->pym_description) . ' ' . $this->billing->getConcept());
        else
            return $this->pym_description . ' ' . $this->billing->getConcept();
    }

    public function getPublisher() {
        return $this->billing->getPublisher();
    }

    public function getPublisherName() {
        return $this->getPublisher()->getName();
    }

    public function getActualBalance() {
        return $this->billing->getBalance();
    }

    public function getDate() {
        return date('d/m/Y', strtotime($this->created_at));
    }

    /*     * *
     * Relationships
     */

    public function billing() {
        return $this->belongsTo('Billing', 'pym_billing_id');
    }

    /*     * *
     * Recibe &$site objeto Site por referencia
     * devuelve true si el publlisher aun no tiene dicho sitio
     * de lo contrario devuelve false y agrega un error.
     */

    private function validateAmount(&$payment) {
        switch ($payment->pym_description) {
            case 'total_payment':
                if ($payment->pym_amount != $payment->billing->getBalance()) {
                    $payment->errors()->add('pym_amount', Lang::get('validation.equal', array('attribute' => Lang::get('validation.attributes.pym_amount'), 'value' => $payment->billing->getBalance())));
                    return false;
                }
                break;
            case 'partial_payment':
            case 'adjustments':
                if ($payment->pym_amount == $payment->billing->getBalance()) {
                    $payment->errors()->add('pym_amount', Lang::get('validation.lower', array('attribute' => Lang::get('validation.attributes.pym_amount'), 'value' => $payment->billing->getBalance())));
                    return false;
                }
                break;
        }
        return true;
    }

    /*     * *
     * Antes de validar
     * valida que el monto no supere el saldo del pago en proceso
     */

    public function beforeValidate() {
        return $this->validateAmount($this);
    }

    /*     * *
     * Despues de guardar
     * actualiza el valor del balance del pago en proceso
     */

    public function afterCreate() {
        $this->billing->discountBalance($this->pym_amount);
        $this->billing->save();
    }

    /*     * *
     * Others
     */

    public static function ofPublisher($publisherId) {
        $allPayments = self::orderBy('pym_id', 'ASC')->get();
        $paymentsOfPublisher = array();
        foreach ($allPayments as $payment) {
            if ($payment->billing->getPublisher()->getId() == $publisherId)
                $paymentsOfPublisher[] = $payment;
        }
        return $paymentsOfPublisher;
    }

}
