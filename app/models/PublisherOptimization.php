<?php

use LaravelBook\Ardent\Ardent;

class PublisherOptimization extends Ardent {

    protected $table = 'publishers_optimization';
    protected $fillable = array();
    protected $guarded = array();

    public function actualize($save = TRUE) {
        $this->hasToAdjust = $this->hasToAdjust();
        $this->hasToBeDisabled = $this->hasToBeDisabled();
        $this->adServing = $this->getAdServing();
        $this->bidReduction = $this->getBidReduction();
        $this->adjustmentCpm = $this->getAdjustmentCpm();
        $this->adjustmentUsd = $this->getAdjustmentUsd();
        $this->profitAdjusted = $this->getProfitAdjusted();
        $this->profitAdserving = $this->getProfitAdserving();
        $this->publisherShare = $this->getPublisherShare();
        $this->publisherDueShare = $this->getPublisherDueShare();
        $this->adtomatikProfitPercent = $this->getAdtomatikProfitPercent();
        $this->adtomatikDueProfitPercent = $this->getAdtomatikDueProfitPercent();
        $this->adtomatikDueProfit = $this->getAdtomatikDueProfit($this->adtomatikDueProfitPercent);
        $this->newAjustedProfitWithParam = $this->getNewAjustedProfit($this->publisherDueShare);
        $this->adtomatikProfitPercentWithParam = $this->getAdtomatikProfitPercent($this->publisherDueShare);
        $this->action = $this->getAction();
        if ($save) {
            var_dump($this->save());
        }
    }

    /*     * *
     * Setters
     */

    public function setImps($param) {
        $this->imps = $param;
    }

    public function setImpsBlank($param) {
        $this->blank = $param;
    }

    public function setImpsPsa($param) {
        $this->psa = $param;
    }

    public function setImpsPsaError($param) {
        $this->psa_error = $param;
    }

    public function setImpsDefaultError($param) {
        $this->default_error = $param;
    }

    public function setImpsDefaultBidder($param) {
        $this->default = $param;
    }

    public function setImpsKept($param) {
        $this->kept = $param;
    }

    public function setImpsResold($param) {
        $this->resold_imps = $param;
    }

    public function setImpsRtb($param) {
        $this->rtb = $param;
    }

    public function setRevenue($param) {
        $this->revenue = $param;
    }

    public function setResellerRevenue($param) {
        $this->resold_rev = $param;
    }

    public function setCost($param) {
        $this->cost = $param;
    }

    public function setProfit($param) {
        $this->profit = $param;
    }

    public function setPublisherName($param) {
        $this->publisher_name = $param;
    }

    public function setSiteName($param) {
        $this->site_name = $param;
    }

    public function setPlacementName($param) {
        $this->placement_name = $param;
    }

    public function setAdserver($adserver) {
        $this->adserver()->associate($adserver);
    }

    public function setPublisher($key, $adserverId) {
        $publisher = DB::table('adserver_publisher')
                ->where('adserver_id', $adserverId)
                ->where('adv_pbl_adserver_key', $key)
                ->limit(1)
                ->get();
        $this->publisher()->associate(Publisher::find($publisher[0]->publisher_id));
    }

    public function setSite($key, $adserverId) {
        $site = DB::table('adserver_site')
                ->where('adserver_id', $adserverId)
                ->where('adv_sit_adserver_key', $key)
                ->limit(1)
                ->get();
        if (count($site)) {
            $this->site()->associate(Site::find($site[0]->site_id));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setPlacement($key, $siteId) {
        $placement = DB::table('placements')
                ->where('plc_site_id', $siteId)
                ->where('plc_adserver_key', $key)
                ->limit(1)
                ->get();
        if (count($placement)) {
            $this->placement()->associate(Placement::find($placement[0]->plc_id));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setCountry($key, $adserverId) {
        $country = DB::table('adserver_country')
                ->where('adserver_id', $adserverId)
                ->where('adv_cnt_adserver_key', $key)
                ->limit(1)
                ->get();
        if (count($country)) {
            $this->country()->associate(Country::find($country[0]->country_id));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*     * *
     * Getters
     */

    public function getID() {
        return $this->id;
    }

    public function getImps() {
        return $this->imps;
    }

    public function getImpsBlank() {
        return $this->blank;
    }

    public function getImpsPsa() {
        return $this->psa;
    }

    public function getImpsPsaError() {
        return $this->psa_error;
    }

    public function getImpsDefaultError() {
        return $this->default_error;
    }

    public function getImpsDefaultBidder() {
        return $this->default;
    }

    public function getImpsKept() {
        return $this->kept;
    }

    public function getImpsResold() {
        return $this->resold_imps;
    }

    public function getImpsRtb() {
        return $this->rtb;
    }

    public function getRevenue() {
        return $this->revenue;
    }

    public function getResellerRevenue() {
        return $this->resold_rev;
    }

    public function getCost() {
        return $this->cost;
    }

    public function getProfit() {
        return $this->profit;
    }

    public function getPublisherName() {
        return $this->publisher_name;
    }

    public function getSiteName() {
        return $this->site_name;
    }

    public function getPlacementName() {
        return $this->placement_name;
    }

    public function getCountryName() {
        return $this->country->cnt_id;
    }

    public function getAdserverName() {
        return $this->adserver->getName();
    }

    public function getPublisherId() {
        return $this->publisher->getId();
    }

    public function getPaymentRule() {
        return PaymentRule::getPaymentRule($this->placement->getId(), $this->country->cnt_id);
    }

    public function isOptimized() {
        if ($this->optimized == 0)
            return false;
        else
            return true;
    }

    /*     * *
     * ( ( Imps Blank + Imps Psa + Imps Psa Error + Imps Default Error + Imps Default Bidder + Imps Kept ) / 1000 ) x Costo Adserving CPM Constante = Adserving
     */

    public function getAdServing() {
        return (($this->getImpsBlank() + $this->getImpsPsa() + $this->getImpsPsaError() + $this->getImpsDefaultError() + $this->getImpsDefaultBidder() + $this->getImpsKept()) / 1000) * str_replace(',', '.', Constant::value('adserving_cost'));
    }

    /*     * *
     * ( Reseller Revenue / Porcentaje de Bid Reduction Constante ) - Reseller Revenue = Bid Reduction
     */

    public function getBidReduction() {
        return ($this->getResellerRevenue() / str_replace(',', '.', Constant::value('bid_reduction_percent'))) - $this->getResellerRevenue();
    }

    /*     * *
     * Si ( Bid Reduction / Imps Resold x 1000 ) < Impuesto al Resold Constante
     * Entonces Impuesto al Resold Constante - ( Bid Reduction / Imps Resold x 1000 ) = Ajuste CPM
     */

    public function getAdjustmentCpm() {
        $bidRed = $this->getBidReduction();
        if ($this->getImpsResold() != 0) {
            if (($bidRed / $this->getImpsResold() * 1000) < str_replace(',', '.', Constant::value('resold_tax')))
                return str_replace(',', '.', Constant::value('resold_tax')) - ($bidRed / $this->getImpsResold() * 1000);
            else
                return 0;
        } else
            return 0;
    }

    /*     * *
     * Si ( Ajuste de CPM x Imps Resold ) <> 0
     * Entonces ( Ajuste de CPM x Imps Resold ) / 1000 = Ajuste Usd
     */

    public function getAdjustmentUsd() {
        $adjCpm = $this->getAdjustmentCpm();
        if (($adjCpm * $this->getImpsResold()) != 0) {
            return ($adjCpm * $this->getImpsResold()) / 1000;
        } else
            return 0;
    }

    /*     * *
     * Profit - Ajuste Usd - Adserving = Profit Ajustado
     */

    public function getProfitAdjusted() {
        return $this->getProfit() - $this->getAdjustmentUsd() - $this->getAdServing();
    }

    /*     * *
     * Profit - Adserving = Profit Adserving
     */

    public function getProfitAdserving() {
        return $this->getProfit() - $this->getAdServing();
    }

    /*     * *
     * ( Costo x 100 ) / Revenue = Share Actual (%)
     */

    public function getPublisherShare() {
        try {
            return ($this->getCost() * 100) / $this->getRevenue();
        } catch (Exception $ex) {
            return 0;
        }
    }

    /*     * *
     * ( Profit Ajustado x 100 ) / Costo = Ajuste del Share Actual (para no perder)
     * Si el ajuste necesario es mayor a 100% entonces el publisher requiere ser desactivado
     */

    public function getAjutmentOfShare() {
        try {
            $ajustment = ($this->getProfitAdjusted() * 100) / $this->getCost();
            if ($ajustment < -100 || $ajustment > 100)
                return 100;
            elseif ($ajustment == -100)
                return 0;
            elseif ($ajustment == 100)
                return 100;
            else
                return abs($ajustment);
        } catch (Exception $ex) {
            return 0;
        }
    }

    public function hasToBeDisabled() {
        $ajustmentOfShare = $this->getPublisherDueShare();
        if ($ajustmentOfShare < 1)
            return TRUE;
        else
            return FALSE;
    }

    public function getAction() {
        $new_share = $this->getPublisherDueShare();
        $actual_share = $this->getPublisherShare();
        if (floor($new_share) > floor($actual_share)) {
            return "UP";
        } elseif (floor($new_share) < floor($actual_share)) {
            return "DOWN";
        } else {
            return "EQUAL";
        }
    }

    /*     * *
     * ( Share Actual x ( 100 - Ajuste del Share Actual ) ) / 100 = Nuevo Share (%)
     */

    public function getPublisherNewShare() {
        try {
            return $this->getPublisherDueShare();
        } catch (Exception $ex) {
            return 0;
        }
    }

    /*     * *
     * ( Nuevo Share x Revenue ) / 100 = Costo Estimado
     */

    public function getNewCost($share = NULL) {
        if ($share == NULL) {
            return ($this->getPublisherNewShare() * $this->getRevenue()) / 100;
        } else {
            return ($share * $this->getRevenue()) / 100;
        }
    }

    /*     * *
     * Revenue - Costo Estimado = Profit Estimado
     */

    public function getNewProfit($share = NULL) {
        if ($share == NULL) {
            return $this->getRevenue() - $this->getNewCost();
        } else {
            return $this->getRevenue() - $this->getNewCost($share);
        }
    }

    /*     * *
     * Profit Estimado - Ajuste de Usd - Adserving = Profit Ajustado Estimado
     */

    public function getNewAjustedProfit($share = NULL) {
        if ($share == NULL) {
            return $this->getNewProfit() - $this->getAdjustmentUsd() - $this->getAdServing();
        } else {
            return $this->getNewProfit($share) - $this->getAdjustmentUsd() - $this->getAdServing();
        }
    }

    /*     * *
     * Conversion de (%) a Double
     */

    public function getDoubleNewRevShare() {
        $decimal = floor($this->getPublisherDueShare());
        return str_replace(',', '.', ($decimal / 100));
    }

    /*     * *
     * Porcentaje de Profit de Adtomatik
     */

    public function getAdtomatikProfitPercent($share = NULL) {
        $newRevenue = $this->getRevenue() - $this->getAdServing(); //Nuevo Revenue
        $multipler = 1;
        if ($newRevenue < 0) {
            $multipler = -1;
        }
        if ($newRevenue == 0) {
            return 0;
        }
        try {
            if ($share)
                return 100 / $newRevenue * $this->getNewAjustedProfit($share) * $multipler;
            else
                return 100 / $newRevenue * $this->getProfitAdjusted() * $multipler;
        } catch (Exception $ex) {
            return 0;
        }
    }

    /*     * *
     * Profit de Adtomatik Conveniente
     */

    public function getAdtomatikDueProfit($percent) {
        if (($this->getRevenue() - $this->getAdServing()) == 0)
            return 0;
        else
            return $percent / (100 / ($this->getRevenue() - $this->getAdServing()));
    }

    /*     * *
     * Porcentaje de Profit de Adtomatik Conveniente
     */

    public function getAdtomatikDueProfitPercent() {
        $minimum_adtomatik_profit = Constant::value('minimum_adtomatik_profit');
        $percent_to_adt = $minimum_adtomatik_profit;
        $actual_adt_profit_percent = $this->getAdtomatikProfitPercent();
        if ($actual_adt_profit_percent >= ($minimum_adtomatik_profit * 2)) {
            $percent_to_adt = $actual_adt_profit_percent / 2;
        }
        return $percent_to_adt;
    }

    /*     * *
     * Porcentaje de Revenue Share debido del publisher
     */

    public function getPublisherDueShare() {
        $percent_to_adt = $this->getAdtomatikDueProfitPercent();
        if ($this->getRevenue() == 0)
            return 0;
        $share = (100 - (($this->getAdtomatikDueProfit($percent_to_adt) + $this->getAdServing() + $this->getAdjustmentUsd()) * 100 / $this->getRevenue()));
        if ($share > Constant::value('default_revenue_share_appnexus')) {
            return Constant::value('default_revenue_share_appnexus');
        } elseif ($share < 0) {
            return 0;
        } else {
            return $share;
        }
        /* if ($this->getRevenue() !== 0) {
          $newShare = (100 - (($this->getAdtomatikDueProfit() + $this->getAdServing() + $this->getAdjustmentUsd()) * 100 / $this->getRevenue()));
          if ($newShare < Constant::value('minimum_publisher_share')) {
          if (number_format($this->getNewAjustedProfit(Constant::value('minimum_publisher_share')), 3) < (-0.0009)) {
          return $this->getPublisherNewShare();
          } else {
          return Constant::value('minimum_publisher_share');
          }
          } else {
          return $newShare;
          }
          } else
          return 0; */
    }

    /*     * *
     * Indica si es el mismo dia a hoy
     */

    public function isSameDate($date = NULL) {
        if ($date == NULL)
            return date('Y-m-d', strtotime($this->created_at)) == date('Y-m-d');
        else
            return date('Y-m-d', strtotime($this->created_at)) == date('Y-m-d', strtotime($date));
    }

    /*     * *
     * Condiciones para ser ajustado:
     * >> Imps Resold <> 0
     * >> Profit Ajustado < -0.009
     */

    public function hasToAdjust() {
        //minimum_adtomatik_profit
        //minimum_publisher_share
        if ($this->getImpsResold() != 0) {
            if (floor($this->getPublisherDueShare()) != floor($this->getPublisherShare()))
                return true;
            return false;
        }
        return false;
    }

    /*     * *
     * Relationships
     */

    public function publisher() {
        return $this->belongsTo('Publisher', 'publisher_id');
    }

    public function site() {
        return $this->belongsTo('Site', 'site_id');
    }

    public function placement() {
        return $this->belongsTo('Placement', 'placement_id');
    }

    public function country() {
        return $this->belongsTo('Country', 'country_id');
    }

    public function adserver() {
        return $this->belongsTo('Adserver', 'adserver_id');
    }

    /*     * *
     * Static Functions & Methods
     */

    /*     * *
     * Limpieza de la tabla, de los dias anteriores
     */

    private static function deleteRowsBeforeSameDays() {
        $days = DB::statement('delete from publishers_optimization where created_at < DATE_SUB(NOW() , INTERVAL ' . Constant::value('day_to_delete_publishers_optimization') . ' DAY)');
    }

    /*     * *
     * Trae todas las fechas de las cuales hay datos
     */

    public static function getAllDates() {
        $dates = DB::select(DB::raw('select DATE(created_at) AS day from publishers_optimization GROUP BY DATE(created_at)'));
        return $dates;
    }

    /*     * *
     * Carga los datos del adserver
     */

    public static function fillTable($trys = 0) {
        //delete rows from table publishers_optimization that has cration date older than constant
        self::deleteRowsBeforeSameDays();
        //get keys from all publishers of same adserver
        $publisher_keys = DB::table('adserver_publisher')
                ->join('publishers', 'publishers.pbl_id', '=', 'adserver_publisher.publisher_id')
                ->where('publishers.pbl_has_to_optimize', '1')
                ->where('adserver_id', '2')
                ->select('adserver_publisher.*')
                ->get();

        //get report to publishers optimization
        $res = Api::getPublishersOptimization(2, $publisher_keys);
        if (count($res) == 0) {
            //var_dump('Fallo en el reporte');
            if ($trys <= 10) {
                PublisherOptimization::fillTable($trys++);
                return TRUE;
            } else {
                echo 'Pasados 10 intentos no se pudo ejecutar el reporte' . "\n";
                return FALSE;
            }
        }
        $adserver = Adserver::find(2);
        $i = 0;
        foreach ($res as $row) {
            if ($row->imps >= 2000) {

                $po = new PublisherOptimization();

                if (!$po->setSite($row->site_id, 2)) {
                    echo "\nFalta sitio:";
                    echo "\t" . $row->site_id . ' >> ' . $row->site_name;
                    continue;
                }
                if (!$po->setPlacement($row->placement_id, $po->site->getId())) {
                    echo "\nFalta espacio:";
                    echo "\t" . $row->placement_id . ' >> ' . $row->placement_name;
                    continue;
                }
                if (!$po->setCountry($row->geo_country, 2)) {
                    echo "\nFalta pais:";
                    echo "\n" . $row->geo_country;
                    continue;
                }

                $po->setImps($row->imps);
                $po->setImpsBlank($row->imps_blank);
                $po->setImpsPsa($row->imps_psa);
                $po->setImpsPsaError($row->imps_psa_error);
                $po->setImpsDefaultError($row->imps_default_error);
                $po->setImpsDefaultBidder($row->imps_default_bidder);
                $po->setImpsKept($row->imps_kept);
                $po->setImpsResold($row->imps_resold);
                $po->setImpsRtb($row->imps_rtb);
                $po->setRevenue($row->revenue);
                $po->setResellerRevenue($row->reseller_revenue);
                $po->setCost($row->cost);
                $po->setProfit($row->profit);
                $po->setPublisherName($row->publisher_name);
                $po->setSiteName($row->site_name);
                $po->setPlacementName($row->placement_name);
                $po->setPublisher($row->publisher_id, $adserver->getId());
                $po->setAdserver($adserver);
                $payment_rule = PaymentRule::getPaymentRule($po->site->getId(), $po->country->cnt_id);
                if ($payment_rule) {
                    if (!$payment_rule->pasedTwoDays()) {
                        // Si el Publisher fue optimizado y aun no han pasado 48hs de la ultima optimizacion
                        // Se setean el Costo Estimado y Profit Estimado calculados en base al Nuevo Share
                        echo "\n";
                        echo $payment_rule->share . " - Share Actualizado.\n";
                        $po->setCost($po->getNewCost($payment_rule->share));
                        echo 'Costo real ' . number_format($row->cost, 3) . ' Costo estimado ' . number_format($po->getCost(), 3) . "\n";
                        $po->setProfit($po->getNewProfit($payment_rule->share));
                        echo 'Profit real ' . number_format($row->profit, 3) . ' Profit estimado ' . number_format($po->getProfit(), 3) . "\n";
                        echo "\n";
                    }
                }
                try {
                    $po->save();
                    $po->actualize();
                    $i = $i + 1;
                } catch (Exception $ex) {
                    echo "\n Error: " . $ex->getMessage() . "\n";
                }
            }
        }
        if ($i > 0) {
            echo "\n" . "Se agregaron " . $i . " registros de Appnexus\n";
            return TRUE;
        } else
            echo "\n" . 'Fallo el reporte con ' . $i . ' registros leidos.';
        return FALSE;
    }

    /*     * *
     * Optimiza todos los publishers del dia
     */

    public static function optimizeAllPublishers() {
        $optimizedCount = 0;
        $rows = PublisherOptimization::all();
        foreach ($rows as $row) {
            if (!$row->isSameDate())
                continue;
            if ($row->hasToAdjust()) {
                // Si el ajuste de Share es mayor o igual a 0 entonces el Publisher se optimiza
                if ($row->getPublisherDueShare() >= 0) {
                    try {
                        echo $row->site->getId() . "\n";
                        //Agrega o modifica Payment Rule en el adserver
                        Api::adjustRevShare(2, $row);
                        $row->optimized = 1;
                        $row->save();
                        $optimized = new OptimizedPublisher();
                        $optimized->publisher_id = $row->getPublisherId();
                        $optimized->site_id = $row->site->getId();
                        $optimized->placement_id = $row->placement->getId();
                        $optimized->country_id = $row->country->cnt_id;
                        $optimized->previous_profit = $row->getProfitAdjusted();
                        $optimized->new_profit = $row->getNewAjustedProfit();
                        $optimized->previous_share = $row->getPublisherShare();
                        $optimized->new_share = $row->getPublisherDueShare();
                        $optimized->optimized_date = date("Y-m-d");
                        $optimized->save();
                        $optimizedCount++;
                        echo 'Fecha ' . date("d/m/Y") . "\n" . ' _ Publisher ' . $row->publisher_name . "\n" . ' _ Profit ajustado antes del cambio $' . number_format($row->previous_profit, 3)
                        . "\n" . ' _ Profit ajustado estimativo despues del cambio $' . number_format($row->new_profit, 3) . "\n" . ' _ Share anterior ' . floor($row->previous_share) . '%'
                        . "\n" . ' _ Share nuevo ' . floor($row->new_share) . "%";
                        if ($row->hasToBeDisabled())
                            echo "\n\tDebe ser desactivado";
                        echo "\n\n";
                        sleep(2);
                    } catch (Exception $ex) {
                        echo 'Optimizando el publisher id ' . $row->getPublisherId() . ' - ' . $ex->getMessage() . "\n";
                    }
                }
            }
        }
        if ($optimizedCount == 0) {
            echo "No hubo que optimizar ningun publisher.\n";
        }
    }

}
