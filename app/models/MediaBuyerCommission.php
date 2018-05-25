<?php

use LaravelBook\Ardent\Ardent;

class MediaBuyerCommission extends Ardent {

    protected $fillable = array();
    protected $guarded = array();
    protected $primaryKey = 'mbc_id';

    /*     * *
     * Setters
     */

    public function setAdministrator($adminId) {
        $this->administrator()->associate(Administrator::find($adminId));
    }

    public function setAdserver($adsId) {
        $this->adserver()->associate(Adserver::find($adsId));
    }

    public function setPeriod($date) {
        $this->mbc_period = $date;
    }

    public function setImps($imps) {
        $this->mbc_imps = $imps;
    }

    public function setRevenue($revenue) {
        $this->mbc_revenue = $revenue;
    }

    public function setCost($cost) {
        $this->mbc_cost = $cost;
    }

    public function setProfit($profit) {
        $this->mbc_profit = $profit;
    }

    public function setCommission($percent) {
        $this->mbc_commission = $this->mbc_profit / 100 * $percent;
    }

    /*     * *
     * Getters
     */

    public function getImps() {
        return $this->mbc_imps;
    }

    public function getRevenue() {
        return $this->mbc_revenue;
    }

    public function getCost() {
        return $this->mbc_cost;
    }

    public function getProfit() {
        return $this->mbc_profit;
    }

    public function getCommission() {
        return $this->mbc_commission;
    }

    public function getMonth() {
        return date("m/Y", strtotime($this->mbc_period));
    }

    /*     * *
     * Relationships
     */

    public function administrator() {
        return $this->belongsTo('Administrator', 'mbc_administrator_id');
    }

    public function adserver() {
        return $this->belongsTo('Adserver', 'mbc_adserver_id');
    }

    public static function calculateData($date = 'last_month') {
        $range = $date;
        if ($date != 'last_month') {
            $range = date('Y-m-01', strtotime($date));
        } else {
            $range = date('Y-m-01', strtotime(date('Y-m') . " -1 month"));
        }
        echo "\t\t-- Fecha: " . date('F Y', strtotime($range)) . "\n";
        $admins = Administrator::all();
        foreach ($admins as $admin) {
            $adservers = $admin->adservers;
            if (count($adservers) > 0) {
                echo "Media Buyer " . $admin->user->getEmail() . "\n";
            }
            foreach ($adservers as $adserver) {
                if ($adserver->getId() == 4 || $adserver->getId() == 1) {
                    continue;
                }
                echo '- Adserver: ' . $adserver->getName() . "\n";
                $trys = 6;
                while (!Api::getMediaBuyerReport($adserver->getId(), $admin, $range) && ($trys > 0)) {
                    $trys = $trys - 1;
                    echo "\tFallo en el reporte. Intento Nro" . $trys . "\n";
                }
            }
        }
    }

    private static function getAdUnits($publisher = NULL) {
        if (!$publisher) {
            //All Placements
            return Placement::getByAdserverToOptimize($this->adserver->getId());
        } else {
            //Publisher adunits
            return $publisher->getAllPlacements();
        }
    }

}
