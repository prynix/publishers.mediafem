<?php

use LaravelBook\Ardent\Ardent;

class PublisherOptimizationDfp extends Ardent {

    protected $table = 'publishers_optimization_dfp';
    protected $fillable = array();
    protected $guarded = array();

    /*     * *
     * Setters
     */

    public function setImpsAdserver($param) {
        $this->adserver_imps = $param;
    }

    public function setImpsExchange($param) {
        $this->exchange_imps = $param;
    }

    public function setRevenueAdserver($param) {
        $this->revenue_adserver = $param;
    }

    public function setRevenueExchange($param) {
        $this->revenue_exchange = $param;
    }

    public function setUnfilledImps($param) {
        $this->unfilled_imps = $param;
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

    //Editar
    public function setPublisher() {
        $this->publisher()->associate($this->placement->site->publisher);
    }

    //Editar
    public function setSite() {
        $this->site()->associate($this->placement->site);
    }

    public function setPlacement($key) {
        $placement = DB::table('placements')
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

    public function getImpsAdserver() {
        return $this->adserver_imps;
    }

    public function getImpsExchange() {
        return $this->exchange_imps;
    }

    public function getUnfilledImps() {
        return $this->unfilled_imps;
    }

    public function getImps($type = 'adserver') {
        if ($type == 'adserver')
            return $this->getImpsAdserver() + $this->getUnfilledImps();
        else
            return $this->getImpsExchange();
    }

    public function getRevenueAdserver() {
        return $this->revenue_adserving;
    }

    public function getRevenueExchange() {
        return $this->revenue_exchange;
    }

    public function getRevenue($type = 'adserver') {
        if ($type == 'adserver')
            return $this->getRevenueAdserver();
        else
            return $this->getRevenueExchange();
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

    public function getActualShare($type = 'adserver') {
        $payment_rule = $this->getPaymentRule($type);
        $share = 0;
        if ($payment_rule)
            $share = $payment_rule->share;
        else
            $share = Constant::value('revenue_' . $type . '_dfp');
        return $share;
    }

    public function getAction($type = 'adserver') {
        $optimized = 'optimized_'.$type;
        if ($this->$optimized == 0)
            return 'EQUAL';
        elseif ($this->$optimized == 1)
            return 'DOWN';
        else
            return 'UP';
    }

    public function changeShare($share, $type = 'adserver') {
        $actual_share = $this->getActualShare($type);
        if (floor($actual_share) != floor($share)) {
            $payment_rule = $this->getPaymentRule($type);
            if ($payment_rule) {
                $payment_rule->share = $share;
                $payment_rule->save();
            } else {
                PaymentRule::newPaymentRule($this->placement->getId(), $this->country->cnt_id, 0, $type, $share);
            }
            $optimized = 'optimized_' . $type;
            if (floor($actual_share) > floor($share)) {
                $this->$optimized = 1;
            } else {
                $this->$optimized = 2;
            }
            $this->save();
        }
    }

    public function getPaymentRule($type = 'adserver') {
        return PaymentRule::getPaymentRule($this->placement->getId(), $this->country->cnt_id, $type);
    }

    public function getAdservingCost($type = 'adserver') {
        return $this->getImps($type) / 1000 * Constant::value('adserving_cost_dfp_' . $type);
    }

    public function getRevenueWithoutAdserving($type = 'adserver') {
        return $this->getRevenue($type) - $this->getAdservingCost($type);
    }

    public function getProfit($share = NULL, $type = 'adserver') {
        return $this->getRevenueWithoutAdserving($type) - $this->getCost($share, $type);
    }

    public function getProfitPercent($share = NULL, $type = 'adserver') {
        $revenueWithoutAdserving = $this->getRevenueWithoutAdserving($type);
        $profit = $this->getProfit($share, $type);
        if ($revenueWithoutAdserving == 0)
            return 0;
        $percent = $profit * 100 / $revenueWithoutAdserving;
        if (($revenueWithoutAdserving < 0) && ($profit < 0)) {
            return $percent * (-1);
        } else {
            return $percent;
        }
    }

    public function getDueProfitPercent($type = 'adserver') {
        $minimum_adtomatik_profit = Constant::value('minimum_adtomatik_profit_' . $type);
        $percent_to_adt = $minimum_adtomatik_profit;
        $actual_adt_profit_percent = $this->getProfitPercent(NULL, $type);
        if ($actual_adt_profit_percent >= ($minimum_adtomatik_profit * 2)) {
            $percent_to_adt = $actual_adt_profit_percent / 2;
        }
        return $percent_to_adt;
    }

    public function getDueShare($type = 'adserver') {
        $revenueWithoutAdserving = $this->getRevenueWithoutAdserving($type);
        $profit = $this->getProfit(NULL, $type);
        //Casos en los que puede dar error!
        if ((($revenueWithoutAdserving < 0) && ($profit < 0)) || ($this->getRevenue($type) == 0)) {
            return 0;
        }
        $x = (($this->getDueProfitPercent($type) * $revenueWithoutAdserving / 100) - $revenueWithoutAdserving) * (-1);
        $share = $x / ($this->getRevenue($type) / 100);
        $maximum_share = Constant::value('revenue_' . $type . '_dfp');
        if ($share > $maximum_share)
            return $maximum_share;
        return $share;
    }

    public function isOptimized($type = 'adserver') {
        $optimized = 'optimized_' . $type;
        if ($this->$optimized == 0)
            return false;
        else
            return true;
    }

    /*     * *
     * Revenue con el share aplicado
     */

    public function getCost($share = NULL, $type = 'adserver') {
        $paymentRule = $this->getPaymentRule($type);
        if ($share) {
            $share = $share;
        } elseif (!$paymentRule) {
            $share = Constant::value('revenue_' . $type . '_dfp');
        } else {
            $share = $paymentRule->share;
        }
        return $this->getRevenue($type) / 100 * $share;
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
        $days = DB::statement('delete from publishers_optimization_dfp where created_at < DATE_SUB(NOW() , INTERVAL ' . Constant::value('day_to_delete_publishers_optimization') . ' DAY)');
    }

    /*     * *
     * Trae todas las fechas de las cuales hay datos
     */

    public static function getAllDates() {
        $dates = DB::select(DB::raw('select DATE(created_at) AS day from publishers_optimization_dfp GROUP BY DATE(created_at)'));
        return $dates;
    }

    /*     * *
     * Carga los datos del adserver
     */

    public static function fillTable($trys = 0) {
        //delete rows from table publishers_optimization that has cration date older than constant
        self::deleteRowsBeforeSameDays();
        //DB::table('publishers_optimization')->truncate();
        //get report to publishers optimization
        $res = Api::getPublishersOptimization(3, null);
        if ($res == 0) {
            //var_dump('Fallo en el reporte');
            if ($trys <= 10) {
                PublisherOptimizationDfp::fillTable($trys++);
                return TRUE;
            } else {
                echo 'Pasados 10 intentos no se pudo ejecutar el reporte de optimizacion de Dfp' . "\n";
                return FALSE;
            }
        } else {
            if ($res > 0) {
                echo "\n" . "Se agregaron " . $res . " registros de Dfp\n";
                return TRUE;
            } else
                echo "\n" . 'Fallo el reporte con ' . $res . ' registros leidos de Dfp.';
            return FALSE;
        }
    }

    public function hasToAnalize($type = 'adserver') {
        if ($this->getImps($type) < Constant::value('minimum_imps_to_optimize'))
            return false;
        return true;
    }

    public function hasToAdjust($type = 'adserver') {
        if (floor($this->getDueShare($type)) != floor($this->getActualShare($type)))
            return true;
        return false;
    }

    public function hasToBeDisabled($type = 'adserver') {
        if (floor($this->getDueShare($type)) <= 0)
            return TRUE;
        else
            return FALSE;
    }

    /*     * *
     * Optimiza todos los publishers del dia
     */

    public static function optimizeAllPublishers() {
        $optimizedCount = 0;
        $rows = PublisherOptimizationDfp::all();
        try {
            foreach ($rows as $row) {
                if (!$row->isSameDate())
                    continue;
                $optimizedCount = $optimizedCount + PublisherOptimizationDfp::optimize($row, 'adserver');
                $optimizedCount = $optimizedCount + PublisherOptimizationDfp::optimize($row, 'exchange');
            }
            if ($optimizedCount == 0) {
                echo "No hubo que optimizar ningun publisher.\n";
            }
        } catch (Exception $ex) {
            var_dump($ex->getTrace());
        }
    }

    public static function optimize($row, $type) {
        if (!$row->hasToAnalize($type))
            return 0;
        if ($row->hasToAdjust($type)) {
            // Si el ajuste de Share es mayor o igual a 0 entonces el Publisher se optimiza
            $share = $row->getDueShare($type);
            if ($share >= 0) {
                try {
                    echo $row->site->getId() . "\n";
                    $optimized = new OptimizedPublisher();
                    $optimized->publisher_id = $row->publisher->getId();
                    $optimized->site_id = $row->site->getId();
                    $optimized->placement_id = $row->placement->getId();
                    $optimized->country_id = $row->country->cnt_id;
                    $optimized->previous_profit = $row->getProfit(null, $type);
                    $optimized->new_profit = $row->getProfit($share, $type);
                    $optimized->previous_share = $row->getActualShare($type);
                    $optimized->new_share = $share;
                    $optimized->optimized_date = date("Y-m-d");
                    $optimized->comments = $type;
                    $optimized->save();
                    $row->changeShare($share, $type);
                    echo 'Fecha ' . date("d/m/Y") . "\n" . ' _ Publisher ' . $row->publisher_name . "\n" . ' _ Profit ajustado antes del cambio $' . number_format($optimized->previous_profit, 3)
                    . "\n" . ' _ Profit ajustado estimativo despues del cambio $' . number_format($optimized->new_profit, 3) . "\n" . ' _ Share anterior ' . floor($optimized->previous_share) . '%'
                    . "\n" . ' _ Share nuevo ' . floor($optimized->new_share) . "%";
                    if ($row->hasToBeDisabled($type))
                        echo "\n\tDebe ser desactivado";
                    echo "\n\n";
                    return 1;
                } catch (Exception $ex) {
                    echo 'Optimizando el publisher id ' . $row->publisher->getId() . ' - ' . $ex->getMessage() . "\n";
                    return 0;
                }
            }
        }
        return 0;
    }

}
