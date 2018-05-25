<?php
use LaravelBook\Ardent\Ardent;

class BridSite extends Ardent {

    public $autoHydrateEntityFromInput = false;
    public $forceEntityHydrationFromInput = false;
    protected $table = 'brid_sites';
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

    public function getBridId() {
        return $this->brid_id;
    }

    /*     * *
     * SETs
     */

    public function setBridId($id) {
        $this->brid_id = $id;
    }

    public function setSite($siteId) {
        $this->site()->associate(Site::find($siteId));
    }

}

class BridVideo extends Ardent {

    public $autoHydrateEntityFromInput = false;
    public $forceEntityHydrationFromInput = false;
    protected $table = 'brid_videos';
    protected $primaryKey = 'id';
    protected $fillable = array();
    protected $guarded = array();
    public static $rules = array();
    public $adserverId = 0;
    
    public function placement() {
        return $this->belongsTo('Placement', 'placement_id');
    }
    
    /*
     * GETs
     */
    
    public function site() {
        return $this->belongsTo('Site', 'site_id');
    }
    
    public function getId() {
        return $this->id;
    }

    public function getBridId() {
        return $this->brid_id;
    }
    
    public function getUrl() {
        return $this->url;
    }
    
    public function getEmbedCode() {
        return $this->embed_code;
    }
    
    /*     * *
     * SETs
     */

    public function setBridId($id) {
        $this->brid_id = $id;
    }
    
    public function setUrl($url) {
        $this->url = $url;
    }
    
    public function setEmbedCode($embedCode) {
        $this->embed_code = $embedCode;
    }
    
    public function setPlacement($placementId) {
        $this->placement()->associate(Placement::find($placementId));
    }
    
    public function beforeCreate() {
        
        if ($this->adserverId == 0) {
            $this->adserverId = Session::get('adserver.id');
        }
                 
        $res_add_video = Api::newVideo($this->adserverId, $this);
        
        if(!$res_add_video)
            return false;
            
        $this->setBridId($res_add_video->videoId);
        $this->setEmbedCode(htmlspecialchars_decode($res_add_video->embed_code));

        return true; //no permite ejecutar el save()
        
    }
    
    
}
