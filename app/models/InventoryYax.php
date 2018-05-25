<?php

use LaravelBook\Ardent\Ardent;

class InventoryYax extends Ardent {

    protected $table = 'inventory_yax';
    protected $primaryKey = 'inv_yax_id';
    protected $guarded = array();
    public static $rules = array();

    public function publisher() {
        return $this->belongsTo('Publisher', 'inv_yax_publisher_id');
    }

    public static function getReport($data) {

        $data['columns']['imps'] = DB::raw('SUM(inv_yax_imps) as imps');
        $data['columns']['clicks'] = DB::raw('SUM(inv_yax_clicks) as clicks');
        $data['columns']['revenue'] = DB::raw('SUM(inv_yax_revenue) as revenue');

        $report = DB::table('inventory_yax')
                ->select($data['columns'])
                ->where('inv_yax_publisher_id', $data['publisher_id'])
                ->whereBetween('inv_yax_day', array($data['start_date'], $data['end_date']))
                ->groupBy($data['group_by'])
                ->get();

        if ($report)
            return $report;
        return false;
    }

}
