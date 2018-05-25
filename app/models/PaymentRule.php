<?php

use LaravelBook\Ardent\Ardent;

class PaymentRule extends Ardent {

    protected $table = 'payment_rules';
    protected $fillable = array();
    protected $guarded = array();

    public static function getPaymentRule($placementId, $countryId, $name = NULL) {
        if ($name) {
            $payment_rule = PaymentRule::where('placement_id', $placementId)
                            ->where('country_id', $countryId)
                            ->where('name', $name)
                            ->orderBy('id', 'DESC')->limit(1)->get();
        } else {
            $payment_rule = PaymentRule::where('placement_id', $placementId)
                            ->where('country_id', $countryId)
                            ->orderBy('id', 'DESC')->limit(1)->get();
        }
        if (count($payment_rule) > 0)
            return $payment_rule[0];
        else
            return null;
    }

    public static function newPaymentRule($placementId, $countryId, $paymentRuleId, $name, $share) {
        echo "\tGuarda " . $placementId . ' - ' . $countryId . ' - ' . $paymentRuleId . ' - ' . $name . ' - ' . $share . "\n";
        $pr = new PaymentRule();
        $pr->placement()->associate(Placement::find($placementId));
        $pr->country()->associate(Country::find($countryId));
        $pr->payment_rule_id = $paymentRuleId;
        $pr->name = $name;
        $pr->share = $share;
        var_dump($pr->save());
    }

    public static function getGroupByPublisher() {
        $payment_rules_publishers = [];
        $publishers = Publisher::all();
        foreach ($publishers as $publisher) {
            $placements = [];
            foreach ($publisher->getAllPlacements() as $placement) {
                $placements[] = $placement->getId();
            }
            if (count($placements) > 0) {
                $rules = PaymentRule::whereIn('placement_id', $placements)->get();
                if (count($rules) > 0)
                    $payment_rules_publishers[] = $publisher;
            }
        }
        if (count($payment_rules_publishers) > 0) {
            return $payment_rules_publishers;
        } else {
            return NULL;
        }
    }

    public function pasedTwoDays() {
        if (date('Y-m-d', strtotime($this->updated_at)) <= date('Y-m-d', strtotime("-2 day")))
            return true;
        else {
            return false;
        }
    }

    public function placement() {
        return $this->belongsTo('Placement', 'placement_id');
    }

    public function country() {
        return $this->belongsTo('Country', 'country_id');
    }

}
