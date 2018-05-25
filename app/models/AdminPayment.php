<?php

use LaravelBook\Ardent\Ardent;

class AdminPayment extends Ardent {

    protected $fillable = array('admpym_amount', 'admpym_description');
    protected $guarded = array();
    protected $primaryKey = 'admpym_id';
    public static $rules = array(
        'admpym_billing_id' => 'required',
        'admpym_amount' => 'required|numeric|min:0.001',
        'admpym_description' => 'required'
    );

    /*     * *
     * para obtener la descripcion:
     * * * Lang::get('payments.'.$this->admpym_description);
     */
    public static $typeOfPayments = array('total_payment', 'partial_payment', 'adjustments');

    /*     * *
     * Setters
     */

    public function setAmount($amount) {
        $this->admpym_amount = $amount;
    }

    public function setDescription($description) {
        $this->admpym_description = $description;
    }

    public function setDate($date) {
        $this->created_at = date('Y-m-d H:i:s', strtotime($date));
    }

    public function setBilling($billingId) {
        $this->billing()->associate(AdminBilling::find($billingId));
    }
    
    public function revertPayment() {
        $this->billing->setBalance($this->getAmount() + $this->billing->getBalance());
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
        return $this->admpym_id;
    }

    public function getAmount() {
        return $this->admpym_amount;
    }

    public function getConcept() {
        if (($this->admpym_description == 'total_payment') || ($this->admpym_description == 'partial_payment') || ($this->admpym_description == 'adjustments'))
            return uppercaseFirstLetter(Lang::get('payments.' . $this->admpym_description) . ' ' . $this->billing->getConcept());
        else
            return $this->admpym_description . ' ' . $this->billing->getConcept();
    }

    public function getAdministrator() {
        return $this->billing->getAdministrator();
    }

    public function getAdministratorName() {
        return $this->getAdministrator()->user->getEmail();
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
        return $this->belongsTo('AdminBilling', 'admpym_billing_id');
    }

    /*     * *
     * Recibe &$site objeto Site por referencia
     * devuelve true si el publlisher aun no tiene dicho sitio
     * de lo contrario devuelve false y agrega un error.
     */

    private function validateAmount(&$payment) {
        switch ($payment->admpym_description) {
            case 'total_payment':
                if ($payment->admpym_amount != $payment->billing->getBalance()) {
                    $payment->errors()->add('admpym_amount', Lang::get('validation.equal', array('attribute' => Lang::get('validation.attributes.admpym_amount'), 'value' => $payment->billing->getBalance())));
                    return false;
                }
                break;
            case 'partial_payment':
            case 'adjustments':
                if ($payment->admpym_amount == $payment->billing->getBalance()) {
                    $payment->errors()->add('admpym_amount', Lang::get('validation.lower', array('attribute' => Lang::get('validation.attributes.admpym_amount'), 'value' => $payment->billing->getBalance())));
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
        $this->billing->discountBalance($this->admpym_amount);
        $this->billing->save();
    }

    /*     * *
     * Others
     */

    public static function ofAdministrator($adminId) {
        $allPayments = self::orderBy('admpym_id', 'ASC')->get();
        $paymentsOfAdministrator = array();
        foreach ($allPayments as $payment) {
            if ($payment->billing->getAdministrator()->getId() == $adminId)
                $paymentsOfAdministrator[] = $payment;
        }
        return $paymentsOfAdministrator;
    }

}
