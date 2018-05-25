<?php

use LaravelBook\Ardent\Ardent;

class Publisher extends Ardent {

    protected $primaryKey = 'pbl_id';
    protected $fillable = array('pbl_name');
    protected $guarded = array();
    public static $rules = array(
        'pbl_name' => 'required|curl_url',
        'pbl_user_id' => 'required'
    );

    /*     * *
     * private variables
     */
    private $adserver;
    private $adserverKeys;

    /*     * *
     * Getters
     */

    public function getName() {
        return $this->pbl_name;
    }

    public function getId() {
        return $this->pbl_id;
    }

    public function getDaysToBilling() {
        return $this->pbl_days_to_billing;
    }

    public function getRevenue() {
        return $this->pbl_revenue_share;
    }

    public function getShowAlert() {
        if ($this->pbl_alert == '1')
            return TRUE;
        else
            return FALSE;
    }

    public function getTax() {
        return ['isComplete' => $this->isTaxComplete(), 'file' => $this->getTaxFile()];
    }
    
    public function isTaxComplete() {
        if ($this->pbl_tax_complete == '0')
            return false;
        else
            return true;
    }

    public function getTaxFile() {
        return $this->pbl_tax_file;
    }
    
    public static function findByUserID($user_id) {
        return self::where('pbl_user_id', $user_id)->first();
    }

    public function getAdserverKey($idAdserver) {
        foreach ($this->adservers as $key) {
            if ($key->pivot->adserver_id == $idAdserver)
                return $key->pivot->adv_pbl_adserver_key;
        }
        return null;
    }

    public function getFirstAdserverId() {
        $adserver = NULL;
        foreach ($this->adservers as $key) {
            $adserver = $key->pivot->adserver_id;
        }
        return $adserver;
    }

    public function getFirstAdserverName() {
        $adserverName = NULL;
        foreach ($this->adservers as $key) {
            $adserver = Adserver::find($key->pivot->adserver_id);
            $adserverName = $adserver->getName();
        }
        return $adserverName;
    }

    public function hasEarningsOfThatMonth($date) {
        $earnings = $this->earnings;
        $month = date('m', strtotime($date));
        foreach ($earnings as $earning) {
            if ($earning->getMonth() === $month)
                return true;
        }
        return false;
    }

    public function getAllPlacements() {
        $placements = array();
        foreach ($this->sites as $site) {
            foreach ($site->placements as $placement) {
                $placements[] = $placement;
            }
        }
        return $placements;
    }
    
    public static function lastWeekImps() {
        $imps = DB::table('last_week_all_inventories_view')->get();
        return $imps;
    }

    public static function getByAdserverKey($idAdserver, $key) {
        $res = DB::table('adserver_publisher')
                ->where('adserver_id', $idAdserver)
                ->where('adv_pbl_adserver_key', $key)
                ->select('publisher_id')
                ->limit(1)
                ->get();
        return Publisher::find($res[0]->publisher_id);
    }
    
    public static function getByAdserverAndMediaBuyer($adserver_id, $media_buyer_id) {
        $publishers = Publisher::join('adserver_publisher', 'publishers.pbl_id', '=', 'adserver_publisher.publisher_id')
                ->where('adserver_publisher.adserver_id', '=', $adserver_id)
                ->where('publishers.pbl_media_buyer_id', '=', $media_buyer_id)
                ->get();
        return $publishers;
    }

    public static function getBySiteAdserverKey($idAdserver, $key) {
        $res = DB::table('adserver_site')
                ->where('adserver_id', $idAdserver)
                ->where('adv_sit_adserver_key', $key)
                ->select('site_id')
                ->limit(1)
                ->get();
        $site = Site::find($res[0]);
        if ($site) {
            return $site->publisher;
        } else
            return NULL;
    }

    public static function getByAdserver($idAdserver) {
        $res = DB::table('adserver_publisher')
                ->where('adserver_id', $idAdserver)
                ->select('publisher_id')
                ->get();
        if ($res) {
            return $res;
        } else {
            return NULL;
        }
    }

    public static function getByIds($ids, $adserverId) {
        $res = DB::table('adserver_publisher')
                ->whereIn('publisher_id', $ids)
                ->where('adserver_id', $adserverId)
                ->select('publisher_id')
                ->get();
        if ($res) {
            return $res;
        } else {
            return NULL;
        }
    }
    /*     * *
     * Setters
     */

    public function setName($name) {
        $this->pbl_name = $name;
    }

    //@id el id del adserver
    public function setAdServer($id) {
        $this->adserver = Adserver::find($id);
    }

    public function setUser($id) {
        $this->user()->associate(User::find($id));
    }

    public function setMediaBuyer($id) {
        $this->mediaBuyer()->associate(Administrator::find($id));
    }

    public function setApproved($approved) {
        $this->pbl_approved = $approved;
    }

    public function hideAlertAndDeleteYax() {
        $this->pbl_alert = 1;
        $this->save();
        $this->deleteRelationWithYax();
    }

    /*     * *
     * Relaciones con entidades
     */

    public function user() {
        return $this->belongsTo('User', 'pbl_user_id');
    }

    public function mediaBuyer() {
        return $this->belongsTo('Administrator', 'pbl_media_buyer_id');
    }

    public function alerts() {
        return $this->hasMany('Alert', 'alr_publisher_id');
    }

    public function sites() {
        return $this->hasMany('Site', 'sit_publisher_id');
    }

    public function paypalDetail() {
        return $this->hasOne('PaypalDetail', 'ppl_publisher_id');
    }

    public function bankDetail() {
        return $this->hasOne('BankDetail', 'bnk_publisher_id');
    }

    public function adservers() {
        return $this->belongsToMany('Adserver')->withPivot('adv_pbl_adserver_key');
    }

    public function earnings() {
        return $this->hasMany('Earning', 'ern_publisher_id')->orderBy('ern_id', 'DESC');
    }

    public function administrators() {
        return $this->hasMany('Administrator', 'adm_publisher_tester');
    }

    public function imonomy() {
        return $this->hasOne('ImonomyPublisher', 'publisher_id');
    }

    /*     * *
     * Antes de validar
     * limpia la URL
     */

    public function beforeValidate() {
        $this->pbl_name = cleanUrl($this->pbl_name);
    }

    /*     * *
     * Antes de guardar
     * crea el sitio en el adserver
     */

    public function beforeCreate() {
        $this->adserver = Adserver::find(Session::get('adserver.id'));
        $this->adserverKeys = Api::newPublisher($this->adserver->getId(), $this);
        if (is_null($this->adserverKeys['publisher_key']))
            return false; //no permite ejecutar el save()

        if (!Api::assignPaymentRule($this->adserver->getId(), $this->adserverKeys['publisher_key']))
            return false;

        return true; //sigue con el save()
    }

    /*     * *
     * Despues de guardar
     * Crea la relacion adserver-publisher guardando el key previsto por el adserver
     * inserta un registro en la tabla sitio
     */

    public function afterCreate() {
        $this->adservers()->save($this->adserver, array('adv_pbl_adserver_key' => $this->adserverKeys['publisher_key']));
        $site = new Site(array('sit_name' => $this->getName()));
        //$site->setAdServer($this->adserver->getId());
        //$site->setKey($this->adserverKeys['site_key']);
        $site = $this->sites()->save($site);
    }

    public function deleteRelationWithYax() {
        //detach publisher - adserver id 1 (Yax)
        $this->adservers()->detach(1);
        $sites = $this->sites;
        foreach ($sites as $site1) {
            $site = Site::find($site1->getId());
            if ($site->isValidated()) {
                //detach sitio - adserver 1
                $site->adservers()->detach(1);

                //-----------------------Espacios
                //traer section
                $sections = $site->sections;
                foreach ($sections as $section) {
                    //eliminar section
                    $section->delete();
                }
            }
        }
    }

}
