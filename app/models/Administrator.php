<?php

use LaravelBook\Ardent\Ardent;

class Administrator extends Ardent {

    protected $primaryKey = 'adm_id';
    protected $guarded = array();
    public static $rules = array();

    public function user() {
        return $this->belongsTo('User', 'adm_user_id');
    }

    public function publishers() {
        return $this->hasMany('Publisher', 'pbl_media_buyer_id');
    }

    public function adservers() {
        return $this->belongsToMany('Adserver')->withPivot('adm_adv_adserver_key');
    }

    public function publisherTester() {
        return $this->belongsTo('Publisher', 'adm_publisher_tester');
    }

    public function earnings() {
        return $this->hasMany('AdminEarning', 'admern_administrator_id');
    }

    public function group() {
        return $this->belongsTo('Group', 'adm_group_id');
    }

    public function paypalDetail() {
        return $this->hasOne('PaypalDetail', 'ppl_administrator_id');
    }

    public function bankDetail() {
        return $this->hasOne('BankDetail', 'bnk_administrator_id');
    }

    /**
     * Tiene permiso para ver la solapa
     * @param string $tab
     * @return boolean
     */
    public function has($tab) {
        return $this->group->has($tab);
    }

    /*     * *
     * Getters
     */

    public function getId() {
        return $this->adm_id;
    }

    public function getName() {
        if ($this->user->profile->getName())
            return $this->user->profile->getName();
        else
            return "---";
    }

    public function getDaysToBilling() {
        return 90;
    }

    public function getRevenueShare($type = 'freelancer') {
        if ($this->adm_revenue_share == 0)
            if ($type == 'freelancer') {
                return Constant::value('default_revenue_share_freelancers');
            } else {
                return Constant::value('default_revenue_share_media_buyer');
            } else
            return $this->adm_revenue_share;
    }

    public function getActualBalance() {
        return $this->adm_actual_balance;
    }

    public function getCurrentEarnings() {
        $earnings = 0;
        foreach ($this->adservers as $adserver) {
            $row = DB::table('admin_current_earnings_' . Str::lower(Str::camel($adserver->getName())))->where('executive', '=', $this->getId())->select('revenue')->get();
            if (count($row) > 0) {
                $earnings = $earnings + $row[0]->revenue;
            }
        }
        return $earnings / 100 * $this->getRevenueShare();
    }

    public function getAdserverKey($idAdserver) {
        foreach ($this->adservers as $key) {
            if ($key->pivot->adserver_id == $idAdserver)
                return $key->pivot->adm_adv_adserver_key;
        }
        return null;
    }

    public function setPublisherTester($id) {
        $this->publisherTester()->associate(Publisher::find($id));
    }

    public function setAdserverKey($key) {
        $this->adservers()->save($this->adserver, array('adm_adv_adserver_key' => $key));
    }

    public function setRevenueShare($rev_share) {
        $this->adm_revenue_share = $rev_share;
    }

    public function setActualBalance($balance) {
        $this->adm_actual_balance = $balance;
    }

}
