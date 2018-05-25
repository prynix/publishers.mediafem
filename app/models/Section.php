<?php

use LaravelBook\Ardent\Ardent;

class Section extends Ardent {
    protected $primaryKey = 'sct_id';
    protected $fillable = array('sct_site_id', 'sct_name', 'sct_adserver_key');
    protected $guarded = array();
    public static $rules = array(
        'sct_site_id' => 'required'
    );

    public function site() {
        return $this->belongsTo('Site', 'sct_site_id');
    }

    /*     * *
     * Getters
     */

    public function getName() {
        return $this->sct_name;
    }

    public function getAdserverKey() {
        return $this->sct_adserver_key;
    }

    public function getPlacements() {
        $placements = array();
        $sizes = Size::getAdserverSizes(1);
        foreach ($sizes as $size) {
            $placement = new Placement();
            $placement->setName($this->sct_name . ' - ' . $size->getName());
            $placement->setAdserverKey($this->sct_adserver_key);
            $placement->size = $size;
            $placement->site = $this->site;
            $placements[] = $placement;
        }

        return $placements;
    }

    public static function getSectionBySite($site_id) {
        return self::where('sct_site_id', $site_id)->get()->first();
    }

    /*     * *
     * Setters
     */

    public function setName($name) {
        $this->sct_name = $name;
    }

    public function setKey($key) {
        $this->sct_adserver_key = $key;
    }

    public function setSite($id) {
        $this->site()->associate(Site::find($id));
    }

    public function beforeSave() {
        if (null == $this->sct_name)
            $this->sct_name = $this->site->sit_name;
        if (null == $this->sct_adserver_key)
            $this->sct_adserver_key = Api::newSection(1, $this);
        if (is_null($this->sct_adserver_key))
            return false; //sigue con el save()
        return true; //no permite ejecutar el save()*/
    }

}