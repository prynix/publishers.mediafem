<?php
use LaravelBook\Ardent\Ardent;

class Size extends Ardent {

    public $forceEntityHydrationFromInput = false;
    public $autoHydrateEntityFromInput = false;
    protected $primaryKey = 'siz_id';
    protected $fillable = array('siz_height', 'siz_width', 'siz_name', 'siz_is_active', 'siz_size_type_id');
    protected $guarded = array();
    public static $rules = array();

    /***
     * Getters
     */    
    public function getId() {
        return $this->siz_id;
    }
    
    public function getName() {
        return $this->siz_name;
    }
    
    public function getHeight() {
        return $this->siz_height;
    }
    
    public function getWidth() {
        return $this->siz_width;
    }
    
    public function isActive() {
        if ($this->siz_is_active == 1)
            return true;
        return false;
    }
    
    /***
     * Relationships
     */
    public function sizeType() {
        return $this->belongsTo('SizeType', 'siz_size_type_id');
    }

    public function adservers() {
        return $this->belongsToMany('Adserver')->withPivot('adv_siz_is_active', 'adv_siz_value');
    }
    
    /***
     * Others
     */
    public function getAdserverKey($idAdserver) {
            foreach ($this->adservers as $key) {
                if($key->pivot->adserver_id == $idAdserver)
                    return $key->pivot->adv_siz_value;
            }
            return null;
        }
        
    public static function getAdserverSizes($idAdserver) {
        //$sizes = self::where('siz_is_active', '1');
        $sizes = self::all();
        $adserverSizes = array();
        foreach ($sizes as $size) {
            foreach ($size->adservers as $key) {
                if($key->pivot->adserver_id == $idAdserver)
                    $adserverSizes[] = $size;
            }
        }
        return $adserverSizes;
    }
}
