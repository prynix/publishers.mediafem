<?php

class Imonomy {

    private $day;
    private $publisher_adserver_id;
    private $publisher_name;
    private $site_adserver_id;
    private $site_name;
    private $country_adserver_id;
    private $country_name;
    private $views;
    private $imps;
    private $revenue;
    private $ecpm;

    public static function fillInventory() {
        $sites = ImonomySite::all();
        $imonomyApi = new ImonomyApi();
        foreach ($sites as $imonomySite) {
            //En caso de no estar completo el Id del Publisher
            if (!$imonomySite->site->publisher->imonomy) {
                //LEYENDA Falta de Adserver ID
                echo "Falta el ID Imonomy del publisher "
                . $imonomySite->site->publisher->getName()
                . " ID "
                . $imonomySite->site->publisher->getId()
                . "\n";
                continue;
            }
            echo $imonomySite->site->getName()
            . "\n";
            $imonomy_site_id = $imonomySite->site->imonomy->getImonomyId();
            $imonomy_publisher_id = $imonomySite->site->publisher->imonomy->getImonomyId();

            $repeat = TRUE;
            $trys = 10;
            do {
                $repeat = TRUE;

                //Reporte por publisher>sitio
                $rows = $imonomyApi->getReport($imonomy_publisher_id, $imonomy_site_id);

                foreach ($rows as $row) {
                    $column = explode(',', $row);
                    if (count($column) == 8) {
                        $repeat = FALSE;
                        $imonomy = new Imonomy();
                        $imonomy->setDay($column[0]);
                        $imonomy->setPublisherName($column[1]);
                        $imonomy->setSiteName($column[2]);
                        $imonomy->setCountryAdserverId($column[3]);
                        $imonomy->setCountryName($column[3]);
                        $imonomy->setViews($column[4]);
                        $imonomy->setImps($column[5]);
                        $imonomy->setRevenue($column[6]);
                        $imonomy->setEcpm($column[7]);

                        $imonomy->setPublisherAdserverId($imonomy_publisher_id);
                        $imonomy->setSiteAdserverId($imonomy_site_id);

                        $imonomy->saveInventory($imonomySite->site->publisher, $imonomySite->site);
                        echo 'day ' . $imonomy->day . ', country_adserver_id ' . $imonomy->country_adserver_id
                        . ', country_name ' . $imonomy->country_name . ', views ' . $imonomy->views . ', imps '
                        . $imonomy->imps . ', revenue $' . $imonomy->revenue . ', ecpm $' . $imonomy->ecpm . "\n";
                    } else {
                        //LEYENDA Error en el reporte
                        /* echo 'Error en el reporte para '
                          . $imonomySite->site->getName()
                          . " sitio "
                          . " (ID BD: "
                          . $imonomySite->site->getId()
                          . " ID Imonomy: "
                          . $imonomy_site_id
                          . ")\n"; */
                        echo $row . "\n";
                        $trys = 0;
                    }
                }
                if ($repeat) {
                    echo "\tIntento No" . (10 - $trys) . "\n";
                    $trys = $trys - 1;
                }
            } while ($repeat && ($trys > 0));
        }
    }

    /*     * *
     * Filling Imonomy Inventory
     */

    public function saveInventory($publisher, $site) {
        DB::table('inventory_imonomy')
                ->insert(
                        array(
                            'publisher_id' => $publisher->getId(),
                            'site_id' => $site->getId(),
                            'publisher_adserver_id' => $this->publisher_adserver_id,
                            'publisher_name' => $this->publisher_name,
                            'site_adserver_id' => $this->site_adserver_id,
                            'site_name' => $this->site_name,
                            'country_adserver_id' => $this->country_adserver_id,
                            'country_name' => $this->country_name,
                            'views' => $this->views,
                            'imps' => $this->imps,
                            'revenue' => $this->revenue / 100 * Constant::value('default_revenue_share_imonomy'),
                            'ecpm' => $this->ecpm,
                            'day' => $this->day
                        )
        );
    }

    public static function getRevenueByDate($interval, $data = NULL) {
        $interval = getDatetimeByInterval($interval);
        
        if (!$data) {
            $data['publisher_id'] = Session::get('publisher.id');
            $data['start_date'] = $interval['start_date'];
            $data['end_date'] = $interval['end_date'];

            $data['group_by'] = 'publisher_adserver_id';
        }
        $data['columns']['revenue'] = DB::raw('SUM(revenue) as revenue');
        $table = 'inventory_imonomy';

        $report = DB::table($table)
                ->select($data['columns'])
                ->where('publisher_id', $data['publisher_id'])
                ->whereBetween('day', array($data['start_date'], $data['end_date']))
                ->groupBy($data['group_by'])
                ->get();
        if ($report)
            return $report[0]->revenue;
        return 0;
    }

    public static function getReport($type, $interval) {

        $interval_str = $interval;
        $data = ['publisher_id' => null, 'start_date' => null, 'end_date' => null, 'group_by' => null, 'columns' => null];
        $interval = getDatetimeByInterval($interval);

        $data['publisher_id'] = Session::get('publisher.id');
        $data['start_date'] = $interval['start_date'];
        $data['end_date'] = $interval['end_date'];

        switch ($type) {
            case 'site_name';
                $data['group_by'] = 'site_id';
                break;
            case 'country_name';
                $data['group_by'] = 'country_name';
                break;
            case 'day':
                $data['group_by'] = 'day';
                break;
            case 'month':
                $data['group_by'] = 'month';
                break;
            default:
                $data['group_by'] = 'day';
                break;
        }

        switch ($type) {
            case 'site_name';
                $data['columns_db'] = array('site_name');
                break;
            case 'country_name';
                $data['columns_db'] = array('country_name');
                break;
            case 'day':
                $data['columns_db'] = array('day');
                break;
            case 'month':
                $data['columns_db'] = array('month');
                break;
            default:
                $data['columns_db'] = array('day');
                break;
        }

        //$data['columns_db'] = Config::get('columns.' . $data['group_by']);


        $table = 'inventory_imonomy';
        $data['columns_db']['views'] = DB::raw('SUM(views) as views');
        $data['columns_db']['imps'] = DB::raw('SUM(imps) as imps');
        $data['columns_db'][] = DB::raw('(SUM(revenue)/SUM(imps)*1000) as ecpm');
        $data['columns_db']['revenue'] = DB::raw('SUM(revenue) as revenue');
        $data['columns_db']['day'] = DB::raw('DATE_FORMAT(day,"%Y-%m-%d") as day');
        $data['columns_db']['month'] = DB::raw('MONTH(day) as `month`');

        $report = DB::table($table)
                ->select($data['columns_db'])
                ->where('publisher_id', $data['publisher_id'])
                ->whereBetween('day', array($data['start_date'], $data['end_date']))
                ->groupBy($data['group_by'])
                ->get();
        $data['columns'][] = $type;
        $data['columns'][] = 'views';
        $data['columns'][] = 'imps';
        $data['columns'][] = 'ecpm';
        $data['columns'][] = 'revenue';

        $data['date']['start'] = $data['start_date'];
        $data['date']['end'] = $data['end_date'];

        $report = array(
            'report' => $report,
            'columns' => $data['columns'],
            'date' => $data['date']
        );
        return $report;
    }

    /**
     * aaaa-mm-aa
     * @param string $date
     */
    public function setDay($date) {
        $this->day = $date;
    }

    public function setPublisherAdserverId($id) {
        $this->publisher_adserver_id = $id;
    }

    public function setPublisherName($name) {
        $this->publisher_name = $name;
    }

    public function setSiteAdserverId($id) {
        $this->site_adserver_id = $id;
    }

    public function setSiteName($name) {
        $this->site_name = $name;
    }

    public function setCountryAdserverId($id) {
        $this->country_adserver_id = $id;
    }

    public function setCountryName($id) {
        if ($id == 'Other') {
            $this->country_name = Lang::get('countries.--');
        } else {
            $this->country_name = Lang::get('countries.' . strtoupper($id));
        }
    }

    public function setViews($views) {
        $this->views = $views;
    }

    public function setImps($imps) {
        $this->imps = $imps;
    }

    public function setRevenue($revenue) {
        $this->revenue = $revenue;
    }

    public function setEcpm($ecpm) {
        $this->ecpm = $ecpm;
    }

}

use LaravelBook\Ardent\Ardent;

class ImonomySite extends Ardent {

    public $autoHydrateEntityFromInput = false;
    public $forceEntityHydrationFromInput = false;
    protected $table = 'imonomy_site';
    protected $primaryKey = 'id';
    protected $fillable = array();
    protected $guarded = array();
    public static $rules = array();
    public $adserverId = 0;

    public function site() {
        return $this->belongsTo('Site', 'site_id');
    }

    /*
     * GETs
     */

    public function getId() {
        return $this->id;
    }

    public function getImonomyId() {
        return $this->imonomy_id;
    }

    public function getImonomyTag() {
        return $this->imonomy_tag;
    }

    /*     * *
     * SETs
     */

    public function setImonomyId($id) {
        $this->imonomy_id = $id;
    }

    public function setImonomyTag($tag) {
        $this->imonomy_tag = $tag;
    }

    public function setSite($siteId) {
        $this->site()->associate(Site::find($siteId));
    }

}

class ImonomyPublisher extends Ardent {

    public $autoHydrateEntityFromInput = false;
    public $forceEntityHydrationFromInput = false;
    protected $table = 'imonomy_publisher';
    protected $primaryKey = 'id';
    protected $fillable = array();
    protected $guarded = array();
    public static $rules = array();
    public $adserverId = 0;

    public function publisher() {
        return $this->belongsTo('Publisher', 'publisher_id');
    }

    /*
     * GETs
     */

    public function getId() {
        return $this->id;
    }

    public function getImonomyId() {
        return $this->imonomy_id;
    }

    /*     * *
     * SETs
     */

    public function setImonomyId($id) {
        $this->imonomy_id = $id;
    }

    public function setImonomyTag($tag) {
        $this->imonomy_tag = $tag;
    }

    public function setPublisher($publisherId) {
        $this->publisher()->associate(Publisher::find($publisherId));
    }

}
