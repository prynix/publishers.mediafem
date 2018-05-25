<?php

use LaravelBook\Ardent\Ardent;

class Placement extends Ardent {

    public $autoHydrateEntityFromInput = true;
    public $forceEntityHydrationFromInput = true;
    protected $table = 'placements';
    protected $primaryKey = 'plc_id';
    protected $fillable = array('plc_name', 'plc_adserver_key');
    protected $guarded = array();
    public static $rules = array();
    public $adserverId = 0;

    public function site() {
        return $this->belongsTo('Site', 'plc_site_id');
    }

    public function size() {
        return $this->belongsTo('Size', 'plc_size_id');
    }
    
    public function bridVideo(){
        return $this->hasOne('BridVideo', 'placement_id');
    }
    
    public function delete() {
        return Placement::where('plc_id', $this->getId())->delete();
    }
    
    
    /*
     * GETs
     */
    
    public static function getPlacementsBySite($site_id) {
        return self::where('plc_site_id', $site_id)->get();
    }

    public static function getPlacementsBySiteAndSize($siteId, $sizeId){
        return DB::table('placements')
                        ->where('placements.plc_site_id', $siteId)
                        ->where('placements.plc_size_id', $sizeId)
                        ->select('placements.*')
                        ->get();
    }
    
    public static function getPlacementsKeysByCategory($categoryId) {
        return DB::table('placements')
                        ->join('sites', 'sites.sit_id', '=', 'placements.plc_site_id')
                        ->join('category_site', 'sites.sit_id', '=', 'category_site.site_id')
                        ->where('category_site.category_id', $categoryId)
                        ->select('placements.plc_adserver_key')
                        ->get();
    }

    public static function getByKey($key) {
        $res = DB::table('placements')
                ->where('plc_adserver_key', $key)
                ->get();
        return Placement::find($res[0]->plc_id);
    }

    public static function getByAdserver($adserverId) {
        return DB::table('placements')
                        ->join('adserver_site', 'adserver_site.site_id', '=', 'placements.plc_site_id')
                        ->where('adserver_site.adserver_id', $adserverId)
                        ->select('placements.*')
                        ->get();
    }
    
    public static function getByAdserverToOptimize($adserverId) {
        return DB::table('placements')
                        ->join('adserver_site', 'adserver_site.site_id', '=', 'placements.plc_site_id')
                        ->join('sites', 'sites.sit_id', '=', 'placements.plc_site_id')
                        ->join('publishers', 'publishers.pbl_id', '=', 'sites.sit_publisher_id')
                        ->where('adserver_site.adserver_id', $adserverId)
                        ->where('publishers.pbl_has_to_optimize', '1')
                        ->select('placements.*')
                        ->get();
    }

    public function getId() {
        return $this->plc_id;
    }

    public function getName() {
        return $this->plc_name;
    }

    public function getKey() {
        return $this->plc_adserver_key;
    }

    public function getAditionalAdserverKey() {
        return $this->plc_aditional_adserver_key;
    }

    public function getAdserverId() {
        return $this->site->getFirstAdserverId();
    }

    public function getAdserverName() {
        return Api::getAdserverPlacementName($this->getAdserverId(), $this);
    }
    
    /*     * *
     * SETs
     */

    public function setName($name) {
        $this->plc_name = $name;
    }

    public function setAdserverKey($key) {
        $this->plc_adserver_key = $key;
    }

    public function setAditionalAdserverKey($key) {
        $this->plc_aditional_adserver_key = $key;
    }

    public function setSite($siteId) {
        $this->site()->associate(Site::find($siteId));
    }

    public function setSize($sizeId) {
        $this->size()->associate(Size::find($sizeId));
    }
    
    /*     * *
     * Before
     */

    public function beforeCreate() {
        if ($this->adserverId == 0) {
            $this->adserverId = Session::get('adserver.id');
        }
        
        $adserverKey = Api::newPlacement($this->adserverId, $this);
        
        if (is_null($adserverKey))
            return false; //sigue con el save()
        if ($this->adserverId == 3) {
            $this->setAdserverKey($adserverKey->id);
            $this->setAditionalAdserverKey($adserverKey->adUnitCode);
        }elseif($this->adserverId == 4){
            $this->setAdserverKey($adserverKey);
            $this->setAditionalAdserverKey($this->site->getAdserverKey(4));
        }else{
            $this->setAdserverKey($adserverKey);
        }

        return true; //no permite ejecutar el save()
    }
    /*
    public function afterCreate(){
        
    }*/

}
