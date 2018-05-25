<?php

set_time_limit(0);
ini_set('post_max_size', '99999M');
ini_set('upload_max_filesize', '999999M');
ini_set('memory_limit', '999999M');
ini_set('max_execution_time', '99999');
ini_set('max_input_time', '99999');

class InventoryAdmin {
    /*     * *
     * Reports of all publishers
     */

    private static $adserverName;
    private static $adserverId;

    public static function getReportAllPublishers($date, $administrator = NULL) {
        if(!$administrator)
            $admin_id = Session::get('admin.id');
        else
            $admin_id = $administrator;
        $reports = array();
        $adservers = Adserver::all();
        $reportIndex = 0;
        $rows = 0;

        if (!Utility::hasPermission('inventory.all') || $administrator) {
            $publishers = DB::table('publishers')->where('publishers.pbl_media_buyer_id', '=', $admin_id)->select('publishers.pbl_id')->get();
            $publishers = implode(',', array_map(function ($entry) {
                        return $entry->pbl_id;
                    }, $publishers));
            $publishers = explode(',', $publishers);
        }
        foreach ($adservers as $adserver) {
            self::$adserverName = strtolower($adserver->getName());
            self::$adserverId = $adserver->getId();
            $adserverId = self::$adserverId;
            if (Utility::hasPermission('inventory.all') && !$administrator) {
                $reports[] = DB::table('inventory_' . self::$adserverName)
                        ->whereBetween('inventory_' . self::$adserverName . '.day', array($date['start_date'], $date['end_date']))
                        ->groupBy('inventory_' . self::$adserverName . '.publisher_id')
                        ->groupBy('inventory_' . self::$adserverName . '.day')
                        ->select(array(
                            DB::raw("'$adserverId' as adserver"),
                            DB::raw('inventory_' . self::$adserverName . '.publisher_id as id'),
                            DB::raw('inventory_' . self::$adserverName . '.publisher_name as publisher_name'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.imps) as imps'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.clicks) as clicks'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.revenue) as revenue'),
                            DB::raw('DATE_FORMAT(inventory_' . self::$adserverName . '.day,"%Y-%m-%d") as day'),
                            DB::raw('MONTH(inventory_' . self::$adserverName . '.day) as month')))
                        ->get();
            } else {
                $reports[] = DB::table('inventory_' . self::$adserverName)
                        ->whereIn('inventory_' . self::$adserverName . '.publisher_id', $publishers)
                        ->whereBetween('inventory_' . self::$adserverName . '.day', array($date['start_date'], $date['end_date']))
                        ->groupBy('inventory_' . self::$adserverName . '.publisher_id')
                        ->groupBy('inventory_' . self::$adserverName . '.day')
                        ->select(array(
                            DB::raw("'$adserverId' as adserver"),
                            DB::raw('inventory_' . self::$adserverName . '.publisher_id as id'),
                            DB::raw('inventory_' . self::$adserverName . '.publisher_name as publisher_name'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.imps) as imps'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.clicks) as clicks'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.revenue) as revenue'),
                            DB::raw('DATE_FORMAT(inventory_' . self::$adserverName . '.day,"%Y-%m-%d") as day'),
                            DB::raw('MONTH(inventory_' . self::$adserverName . '.day) as month')))
                        ->get();
            }
            $rows += count($reports[$reportIndex]);
            $reportIndex++;
        }

        if ($rows > 0) {
            $reportTotal = array();
            $totals = ['imps' => 0, 'clicks' => 0, 'revenue' => 0, 'ctr' => 0, 'cpm' => 0];
            foreach ($reports as $report) {
                foreach ($report as $row) {
                    $publisher = Publisher::find($row->id);
                    if ($publisher) {
                        $publisher_id = $publisher->pbl_id;
                    } else {
                        continue;
                    }
                    $key = self::in_array_field($publisher_id, 'id', $reportTotal);
                    $totals['imps'] += $row->imps;
                    $totals['clicks'] += $row->clicks;
                    $totals['revenue'] += $row->revenue;
                    if ($key !== NULL) {
// Sum other adserver
                        $reportTotal[$key]->imps += $row->imps;
                        $reportTotal[$key]->clicks += $row->clicks;
                        $reportTotal[$key]->revenue += $row->revenue;
// Calculate values
                        if ($reportTotal[$key]->imps != 0) {
                            $reportTotal[$key]->ctr = ($reportTotal[$key]->clicks / $reportTotal[$key]->imps) * 100;
                            $reportTotal[$key]->cpm = ($reportTotal[$key]->revenue / $reportTotal[$key]->imps) * 1000;
                        } else {
                            $reportTotal[$key]->ctr = 0;
                            $reportTotal[$key]->cpm = 0;
                        }
                    } else {
                        $new_row = new stdClass();
// Add row
                        $new_row->id = $publisher_id;
                        if ($publisher_id == 0)
                            $new_row->column = 'Unknown Publishers';
                        else
                            $new_row->column = $publisher->pbl_name;
                        $new_row->imps = $row->imps;
                        $new_row->clicks = $row->clicks;
                        $new_row->revenue = $row->revenue;
                        $new_row->day = $row->day;
                        $new_row->month = $row->month;
// Calculate values
                        if ($new_row->imps != 0) {
                            $new_row->ctr = ($new_row->clicks / $new_row->imps) * 100;
                            $new_row->cpm = ($new_row->revenue / $new_row->imps) * 1000;
                        } else {
                            $new_row->ctr = 0;
                            $new_row->cpm = 0;
                        }
                        $reportTotal[] = $new_row;
                    }
                }
            }
            $totals['ctr'] = ($totals['clicks'] / $totals['imps']) * 100;
            $totals['cpm'] = ($totals['revenue'] / $totals['imps']) * 1000;
            return ['report' => $reportTotal, 'totals' => $totals];
        }
        return false;
    }

    public static function getReportAllSites($date) {
        $reports = array();
        $adservers = Adserver::all();
        $reportIndex = 0;
        $rows = 0;
        $publishers = DB::table('publishers')->where('publishers.pbl_media_buyer_id', '=', Session::get('admin.id'))->select('publishers.pbl_id')->get();

        $publishers = implode(',', array_map(function ($entry) {
                    return $entry->pbl_id;
                }, $publishers));
        $publishers = explode(',', $publishers);

        foreach ($adservers as $adserver) {
            self::$adserverName = strtolower($adserver->getName());
            self::$adserverId = $adserver->getId();
            $adserverId = self::$adserverId;
            if (Utility::hasPermission('inventory.all')) {
                $reports[] = DB::table('inventory_' . self::$adserverName)
                        ->whereBetween('inventory_' . self::$adserverName . '.day', array($date['start_date'], $date['end_date']))
                        ->groupBy('inventory_' . self::$adserverName . '.site_adserver_id')
                        ->groupBy('inventory_' . self::$adserverName . '.day')
                        ->select(array(
                            DB::raw("'$adserverId' as adserver"),
                            DB::raw('inventory_' . self::$adserverName . '.site_adserver_id as id'),
                            DB::raw('inventory_' . self::$adserverName . '.site_name as site_name'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.imps) as imps'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.clicks) as clicks'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.revenue) as revenue'),
                            DB::raw('DATE_FORMAT(inventory_' . self::$adserverName . '.day,"%Y-%m-%d") as day'),
                            DB::raw('MONTH(inventory_' . self::$adserverName . '.day) as month')))
                        ->get();
            } else {
                $reports[] = DB::table('inventory_' . self::$adserverName)
                        ->whereIn('inventory_' . self::$adserverName . '.publisher_id', $publishers)
                        ->whereBetween('inventory_' . self::$adserverName . '.day', array($date['start_date'], $date['end_date']))
                        ->groupBy('inventory_' . self::$adserverName . '.site_adserver_id')
                        ->groupBy('inventory_' . self::$adserverName . '.day')
                        ->select(array(
                            DB::raw("'$adserverId' as adserver"),
                            DB::raw('inventory_' . self::$adserverName . '.site_adserver_id as id'),
                            DB::raw('inventory_' . self::$adserverName . '.site_name as site_name'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.imps) as imps'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.clicks) as clicks'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.revenue) as revenue'),
                            DB::raw('DATE_FORMAT(inventory_' . self::$adserverName . '.day,"%Y-%m-%d") as day'),
                            DB::raw('MONTH(inventory_' . self::$adserverName . '.day) as month')))
                        ->get();
            }
            $rows += count($reports[$reportIndex]);
            $reportIndex++;
        }

        if ($rows > 0) {
            $reportTotal = array();
            $totals = ['imps' => 0, 'clicks' => 0, 'revenue' => 0, 'ctr' => 0, 'cpm' => 0];
            foreach ($reports as $report) {
                foreach ($report as $row) {
                    $site = Site::getAllByAdserverKey($row->id, $row->adserver);
                    //var_dump($row->id);
                    if ($site) {
                        $site_id = $site->sit_id;
                    } else {
                        continue;
                    }
                    $key = self::in_array_field($site_id, 'id', $reportTotal);
                    $totals['imps'] += $row->imps;
                    $totals['clicks'] += $row->clicks;
                    $totals['revenue'] += $row->revenue;
                    if ($key !== NULL) {
// Sum other adserver
                        $reportTotal[$key]->imps += $row->imps;
                        $reportTotal[$key]->clicks += $row->clicks;
                        $reportTotal[$key]->revenue += $row->revenue;
// Calculate values
                        if ($reportTotal[$key]->imps != 0) {
                            $reportTotal[$key]->ctr = ($reportTotal[$key]->clicks / $reportTotal[$key]->imps) * 100;
                            $reportTotal[$key]->cpm = ($reportTotal[$key]->revenue / $reportTotal[$key]->imps) * 1000;
                        } else {
                            $reportTotal[$key]->ctr = 0;
                            $reportTotal[$key]->cpm = 0;
                        }
                    } else {
                        $new_row = new stdClass();
// Add row
                        $new_row->id = $site_id;
                        if ($site_id == 0)
                            $new_row->column = 'Unknown Sites';
                        else
                            $new_row->column = $site->sit_name;
                        $new_row->imps = $row->imps;
                        $new_row->clicks = $row->clicks;
                        $new_row->revenue = $row->revenue;
                        $new_row->day = $row->day;
                        $new_row->month = $row->month;
// Calculate values
                        if ($new_row->imps != 0) {
                            $new_row->ctr = ($new_row->clicks / $new_row->imps) * 100;
                            $new_row->cpm = ($new_row->revenue / $new_row->imps) * 1000;
                        } else {
                            $new_row->ctr = 0;
                            $new_row->cpm = 0;
                        }
                        $reportTotal[] = $new_row;
                    }
                }
            }
            $totals['ctr'] = ($totals['clicks'] / $totals['imps']) * 100;
            $totals['cpm'] = ($totals['revenue'] / $totals['imps']) * 1000;
            return ['report' => $reportTotal, 'totals' => $totals];
        }
        return false;
    }

    public static function getReportAllCountries($date) {
        $reports = array();
        $adservers = Adserver::all();
        $reportIndex = 0;
        $rows = 0;
        $publishers = DB::table('publishers')->where('publishers.pbl_media_buyer_id', '=', Session::get('admin.id'))->select('publishers.pbl_id')->get();

        $publishers = implode(',', array_map(function ($entry) {
                    return $entry->pbl_id;
                }, $publishers));
        $publishers = explode(',', $publishers);
        foreach ($adservers as $adserver) {
            self::$adserverName = strtolower($adserver->getName());
            self::$adserverId = $adserver->getId();
            $adserverId = self::$adserverId;
            if (Utility::hasPermission('inventory.all')) {
                $reports[] = DB::table('inventory_' . self::$adserverName)
                        ->leftJoin(DB::raw("(select adserver_country.country_id as id, adserver_country.country_id as country, 
                                    adserver_country.adv_cnt_adserver_key as country_key 
                                    from adserver_country 
                                    where adserver_country.adserver_id = '$adserverId') country_tbl"), 'country_tbl.country_key', '=', 'inventory_' . self::$adserverName . '.country_adserver_id')
                        ->whereBetween('inventory_' . self::$adserverName . '.day', array($date['start_date'], $date['end_date']))
                        ->groupBy('inventory_' . self::$adserverName . '.site_adserver_id')
                        ->groupBy('inventory_' . self::$adserverName . '.day')
                        ->select(array(
                            DB::raw('country_tbl.id as id'),
                            DB::raw('country_tbl.country as country'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.imps) as imps'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.clicks) as clicks'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.revenue) as revenue'),
                            DB::raw('DATE_FORMAT(inventory_' . self::$adserverName . '.day,"%Y-%m-%d") as day'),
                            DB::raw('MONTH(inventory_' . self::$adserverName . '.day) as month')))
                        ->get();
            } else {
                $reports[] = DB::table('inventory_' . self::$adserverName)
                        ->leftJoin(DB::raw("(select adserver_country.country_id as id, adserver_country.country_id as country, 
                                    adserver_country.adv_cnt_adserver_key as country_key 
                                    from adserver_country 
                                    where adserver_country.adserver_id = '$adserverId') country_tbl"), 'country_tbl.country_key', '=', 'inventory_' . self::$adserverName . '.country_adserver_id')
                        ->whereIn('inventory_' . self::$adserverName . '.publisher_id', $publishers)
                        ->whereBetween('inventory_' . self::$adserverName . '.day', array($date['start_date'], $date['end_date']))
                        ->groupBy('inventory_' . self::$adserverName . '.site_adserver_id')
                        ->groupBy('inventory_' . self::$adserverName . '.day')
                        ->select(array(
                            DB::raw('country_tbl.id as id'),
                            DB::raw('country_tbl.country as country'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.imps) as imps'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.clicks) as clicks'),
                            DB::raw('SUM(inventory_' . self::$adserverName . '.revenue) as revenue'),
                            DB::raw('DATE_FORMAT(inventory_' . self::$adserverName . '.day,"%Y-%m-%d") as day'),
                            DB::raw('MONTH(inventory_' . self::$adserverName . '.day) as month')))
                        ->get();
            }
            $rows += count($reports[$reportIndex]);
            $reportIndex++;
        }

        if ($rows > 0) {
            $reportTotal = array();
            $totals = ['imps' => 0, 'clicks' => 0, 'revenue' => 0, 'ctr' => 0, 'cpm' => 0];
            foreach ($reports as $report) {
                foreach ($report as $row) {
                    if ($row->id == NULL)
                        $row->id = 0;
                    $key = self::in_array_field($row->id, 'id', $reportTotal);
                    $totals['imps'] += $row->imps;
                    $totals['clicks'] += $row->clicks;
                    $totals['revenue'] += $row->revenue;
                    if ($key !== NULL) {
// Sum other adserver
                        $reportTotal[$key]->imps += $row->imps;
                        $reportTotal[$key]->clicks += $row->clicks;
                        $reportTotal[$key]->revenue += $row->revenue;
// Calculate values
                        $reportTotal[$key]->ctr = ($reportTotal[$key]->clicks / $reportTotal[$key]->imps) * 100;
                        $reportTotal[$key]->cpm = ($reportTotal[$key]->revenue / $reportTotal[$key]->imps) * 1000;
                    } else {
                        $new_row = new stdClass();
// Add row
                        $new_row->id = $row->id;
                        $new_row->column = $row->country;
                        $new_row->imps = $row->imps;
                        $new_row->clicks = $row->clicks;
                        $new_row->revenue = $row->revenue;
                        $new_row->day = $row->day;
                        $new_row->month = $row->month;
// Calculate values
                        $new_row->ctr = ($new_row->clicks / $new_row->imps) * 100;
                        $new_row->cpm = ($new_row->revenue / $new_row->imps) * 1000;
                        $reportTotal[] = $new_row;
                    }
                }
            }
            $totals['ctr'] = ($totals['clicks'] / $totals['imps']) * 100;
            $totals['cpm'] = ($totals['revenue'] / $totals['imps']) * 1000;

            return ['report' => $reportTotal, 'totals' => $totals];
        }
        return false;
    }

    private static function in_array_field($needle, $needle_field, $haystack, $strict = false) {
        if ($strict) {
            foreach ($haystack as $key => $item)
                if (isset($item->$needle_field) && $item->$needle_field === $needle)
                    return $key;
        }
        else {
            foreach ($haystack as $key => $item)
                if (isset($item->$needle_field) && $item->$needle_field == $needle)
                    return $key;
        }
        return null;
    }

}
