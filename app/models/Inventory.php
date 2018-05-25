<?php

class Inventory {

    public $adserver;
    private $day;
    private $publisher_adserver_id;
    private $publisher_name;
    private $site_adserver_id;
    private $site_name;
    private $placement_adserver_id;
    private $placement_name;
    private $size_adserver_id;
    private $size_name;
    private $country_adserver_id;
    private $country_name;
    private $imps;
    private $clicks;
    private $revenue;

    function __construct($idAdserver) {
        $this->setAdserver($idAdserver);
    }

    public function setAdserver($id) {
        $this->adserver = Adserver::find($id);
    }

    public function setDay($data) {
        $this->day = date('Y-m-d', strtotime($data));
    }

    public function setPublisherAdserverId($data) {
        $this->publisher_adserver_id = $data;
    }

    public function setPublisherName($data) {
        $this->publisher_name = $data;
    }

    public function setSiteAdserverId($data) {
        $this->site_adserver_id = $data;
    }

    public function setSiteName($data) {
        $this->site_name = $data;
    }

    public function setPlacementAdserverId($data) {
        $this->placement_adserver_id = $data;
    }

    public function setPlacementName($data) {
        $this->placement_name = $data;
    }

    public function setSizeAdserverId($data) {
        $this->size_adserver_id = $data;
    }

    public function setSizeName($data) {
        $this->size_name = $data;
    }

    public function setCountryAdserverId($data) {
        $this->country_adserver_id = $data;
    }

    public function setCountryName($data) {
        $this->country_name = $data;
    }

    public function setImps($data) {
        $this->imps = $data;
    }

    public function setClicks($data) {
        $this->clicks = $data;
    }

    public function setRevenue($data) {
        $this->revenue = $data;
    }

    public function save() {
        $placement = NULL;
        $publisherId = 0;
        if ($this->publisher_adserver_id) {
            $publisher = Publisher::getByAdserverKey($this->adserver->getId(), $this->publisher_adserver_id);
            $publisherId = $publisher->pbl_id;
        } elseif ($this->site_adserver_id) {
            $publisher = Publisher::getBySiteAdserverKey($this->adserver->getId(), $this->site_adserver_id);
            $publisherId = $publisher->getId();
            $this->publisher_adserver_id = $publisher->getAdserverKey($this->adserver->getId());
        } else {
            $placement = Placement::getByKey($this->placement_adserver_id);
            $publisherId = $placement->site->publisher->getId();
            $this->publisher_adserver_id = $placement->site->publisher->getAdserverKey($this->adserver->getId());
            $this->publisher_name = $placement->site->publisher->getName();
            $this->site_adserver_id = $placement->site->getAdserverKey($this->adserver->getId());
            $this->site_name = $placement->site->getName();
        }
        if (!$this->size_adserver_id) {
            if (!$placement) {
                $placement = Placement::getByKey($this->placement_adserver_id);
            }
            $this->size_adserver_id = $placement->size->getName();
            $this->size_name = $placement->size->getName();
        }
        DB::table('inventory_' . strtolower($this->adserver->getName()))
                ->insert(
                        array('publisher_id' => $publisherId,
                            'day' => $this->day,
                            'publisher_adserver_id' => $this->publisher_adserver_id,
                            'publisher_name' => $this->publisher_name,
                            'site_adserver_id' => $this->site_adserver_id,
                            'site_name' => $this->site_name,
                            'placement_adserver_id' => $this->placement_adserver_id,
                            'placement_name' => $this->placement_name,
                            'size_adserver_id' => $this->size_adserver_id,
                            'size_name' => $this->size_name,
                            'country_adserver_id' => $this->country_adserver_id,
                            'country_name' => $this->country_name,
                            'imps' => $this->imps,
                            'clicks' => $this->clicks,
                            'revenue' => $this->revenue
                        )
        );
    }

    public function printRow() {
        echo $this->day,
        ' ' . $this->publisher_adserver_id .
        ' ' . $this->publisher_name .
        ' ' . $this->site_adserver_id .
        ' ' . $this->site_name .
        ' ' . $this->placement_adserver_id .
        ' ' . $this->placement_name .
        ' ' . $this->size_adserver_id .
        ' ' . $this->size_name .
        ' ' . $this->country_adserver_id .
        ' ' . $this->country_name .
        ' ' . $this->imps .
        ' ' . $this->clicks .
        ' ' . $this->revenue . "\n";
    }

    public static function getReport($data, $adserver = NULL) {
        if (!$adserver) {
            $adserver = Adserver::find(Session::get('adserver.id'));
        } else {
            $adserver = Adserver::find($adserver);
        }
        $table = 'inventory_' . strtolower($adserver->getName());

        $data['columns']['imps'] = DB::raw('SUM(imps) as imps');
        $data['columns']['clicks'] = DB::raw('SUM(clicks) as clicks');
        $data['columns'][] = DB::raw('(SUM(clicks)/SUM(imps)*100) as ctr');
        $data['columns'][] = DB::raw('(SUM(revenue)/SUM(imps)*1000) as cpm');
        $data['columns']['revenue'] = DB::raw('SUM(revenue) as revenue');
        $data['columns']['day'] = DB::raw('DATE_FORMAT(day,"%Y-%m-%d") as day');
        $data['columns']['month'] = DB::raw('MONTH(day) as `month`');

        $report = DB::table($table)
                ->select($data['columns'])
                ->where('publisher_id', $data['publisher_id'])
                ->whereBetween('day', array($data['start_date'], $data['end_date']))
                ->groupBy($data['group_by'])
                ->get();

        if ($report)
            return $report;
        return false;
    }

    public static function getRevenueByDate($data, $adserver = NULL) {
        if (!$adserver) {
            $adserver = Adserver::find(Session::get('adserver.id'));
        } else {
            $adserver = Adserver::find($adserver);
        }
        $table = 'inventory_' . strtolower($adserver->getName());

        $data['columns']['revenue'] = DB::raw('SUM(revenue) as revenue');

        $report = DB::table($table)
                ->select($data['columns'])
                ->where('publisher_id', $data['publisher_id'])
                ->whereBetween('day', array($data['start_date'], $data['end_date']))
                ->groupBy($data['group_by'])
                ->get();

        if ($report)
            return $report;
        return false;
    }

    public static function getDataGraph($data, $adserver = NULL) {
        if (!$adserver) {
            $adserver = Adserver::find(Session::get('adserver.id'));
        } else {
            $adserver = Adserver::find($adserver);
        }
        $table = 'inventory_' . strtolower($adserver->getName());

        $data['columns']['day'] = DB::raw('DATE_FORMAT(day,"%m-%d") as day');
        $data['columns']['month'] = DB::raw('MONTH(day) as `month`');
        $data['columns']['revenue'] = DB::raw('SUM(revenue) as revenue');

        $report = DB::table($table)
                ->select($data['columns'])
                ->where('publisher_id', $data['publisher_id'])
                ->whereBetween('day', array($data['start_date'], $data['end_date']))
                ->groupBy($data['group_by'])
                ->get();

        if ($report)
            return $report;
        return false;
    }

    public static function getDataGraphMap($data, $adserver = NULL) {
        if (!$adserver) {
            $adserver = Adserver::find(Session::get('adserver.id'));
        } else {
            $adserver = Adserver::find($adserver);
        }
        $table = 'inventory_' . strtolower($adserver->getName());

        $report = DB::select("select ac.country_id, i.country_name, i.country_adserver_id, SUM(i.revenue) as revenue, DATE_FORMAT(i.day,'%Y-%m-%d') as day from $table as i, adserver_country as ac where i.publisher_id = ? and i.country_adserver_id = ac.adv_cnt_adserver_key and i.day between ? and ? group by i.country_name;", array($data['publisher_id'], $data['start_date'], $data['end_date']));
        /*
          $queries = DB::getQueryLog();
          $last_query = end($queries);

          echo "<pre>";
          var_dump($last_query);
         */
        if ($report)
            return $report;

        return FALSE;
    }

}
