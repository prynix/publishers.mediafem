<?php

use LaravelBook\Ardent\Ardent;

class Site extends Ardent {

    public $autoHydrateEntityFromInput = true;
    public $forceEntityHydrationFromInput = true;
    protected $table = 'sites';
    protected $primaryKey = 'sit_id';
    protected $fillable = array('sit_name', 'sit_domain_list');
    protected $guarded = array();
    public static $rules = array(
        'sit_name' => 'required|curl_url'
    );

    /*     * *
     * Getters
     */

    public function getId() {
        return $this->sit_id;
    }

    public function getName() {
        return $this->sit_name;
    }

    public function getDomainList() {
        return $this->sit_domain_list;
    }

    public function getLineItemId() {
        return $this->sit_lineitem_id;
    }
    
    public function getState() {
        return $this->sit_state;
    }

    public function isValidated() {
        if ($this->sit_is_validated == 1)
            return true;
        return false;
    }

    public function isCategorized() {
        $categories = $this->categories;
        if (count($this->categories) > 0) {
            foreach ($categories as $category) {
                if ($category->isDefaultInAdserver($this->getFirstAdserverId()))
                    return false;
            }
            return true;
        }else {
            return false;
        }
    }

    public function hasCategory($idCategory) {
        $categories = $this->categories;
        foreach ($categories as $category) {
            if ($category->getId() == $idCategory)
                return true;
        }
        return false;
    }

    public function getValidationType() {
        return $this->sit_validation_type;
    }

    public static function getSitesByPublisher($publisher_id) {
        return self::where('sit_publisher_id', $publisher_id)->get();
    }

    public static function getSitesByAdserverHasToBeCategorized($adserver_id) {
        return DB::table('sites')
                        ->join('adserver_site', 'sites.sit_id', '=', 'adserver_site.site_id')
                        ->select('sites.sit_id')
                        ->where('adserver_site.adserver_id', $adserver_id)
                        ->where('sites.sit_categorized_on_adserver', '0')
                        ->get();
    }

    public static function updateSitesSetCategorizedTrue($listSiteIds) {
        DB::table('sites')->whereIn('sit_id', $listSiteIds)->update(array('sit_categorized_on_adserver' => '1'));
    }

    public static function updateSiteSetCategorizedFalse($siteId) {
        DB::table('sites')->where('sit_id', $siteId)->update(array('sit_categorized_on_adserver' => '0'));
    }

    public static function updateSiteSetCategorizedFTrue($siteId) {
        DB::table('sites')->where('sit_id', $siteId)->update(array('sit_categorized_on_adserver' => '1'));
    }

    public static function updateLineItemId($siteId, $lineItemId) {
        try {
            DB::table('sites')->where('sit_id', $siteId)->update(array('sit_lineitem_id' => $lineItemId));
        } catch (Exception $e) {
            echo "error model: " . $e->getMessage();
        }
    }

    public function getFirstAdserverId() {
        $adserver = NULL;
        foreach ($this->adservers as $key) {
            $adserver = $key->pivot->adserver_id;
        }
        return $adserver;
    }

    public function isAlreadyHasPlacementFormat($sizeId) {
        foreach ($this->placements as $placement) {
            if ($placement->size->getId() == $sizeId)
                return true;
        }
        return false;
    }

    /*     * *
     * Devuelve el id del sitio en $idAdserver adserver
     * En caso de no estar registrado devuelve null
     */

    public function getAdserverKey($idAdserver) {
        foreach ($this->adservers as $key) {
            if ($key->pivot->adserver_id == $idAdserver)
                return $key->pivot->adv_sit_adserver_key;
        }
        return null;
    }

    public static function getByAdserverKey($adserverId, $adserverKey) {
        $site = DB::table('sites')
                ->join('adserver_site', 'adserver_site.site_id', '=', 'sites.sit_id')
                ->where('adserver_site.adserver_id', $adserverId)
                ->where('adserver_site.adv_sit_adserver_key', $adserverKey)
                ->get();
        if ($site)
            return $site[0]->sit_name;
        else {
            $adserver = Adserver::find($adserverId);
            $site = DB::table('inventory_' . $adserver->getName())
                    ->where('inventory_' . $adserver->getName() . '.site_adserver_id', $adserverKey)
                    ->first();
            if ($site)
                return $site->site_name;
            else
                return '--------';
        }
    }

    public static function getAllByAdserverKey($adserverKey, $adserverId) {
        $site = DB::table('sites')
                ->join('adserver_site', 'adserver_site.site_id', '=', 'sites.sit_id')
                ->where('adserver_site.adserver_id', $adserverId)
                ->where('adserver_site.adv_sit_adserver_key', $adserverKey)
                ->select('sit_id', 'sit_name')
                ->limit(1)
                ->get();
        if ($site)
            return $site[0];
        else {
            return NULL;
        }
    }

    public function getArrayOfAdserverCategories($idAdserver) {
        $category_keys = array();
        foreach ($this->categories as $category) {
            $keys = $category->getAdserverKey($idAdserver);
            if ($keys) {
                foreach ($keys as $key) {
                    if (!in_array($key, $category_keys))
                        $category_keys[] = $key;
                }
            }else {
                $this->categories()->detach($category->getId());
            }
        }
        return $category_keys;
    }

    public static function getValidatedSites($media_buyer_id = NULL) {
        if ($media_buyer_id)
            $sites = DB::table('validated_sites')->where('media_buyer_id', $media_buyer_id)->orderBy('id', 'desc')->get();
        else
            $sites = DB::table('validated_sites')->get();
        return $sites;
    }

    public static function getUnvalidatedSites($media_buyer_id = NULL) {
        if ($media_buyer_id)
            $sites = DB::table('unvalidated_sites')->where('media_buyer_id', $media_buyer_id)->orderBy('id', 'desc')->get();
        else
            $sites = DB::table('unvalidated_sites')->get();
        return $sites;
    }

    /*     * *
     * Setters
     */

    public function setAdServer($idAdserver) {
        $this->adserver = Adserver::find($idAdserver);
    }

    public function setPublisher($publisherId) {
        $this->publisher()->associate(Publisher::find($publisherId));
    }

    public function setValidated($validated) {
        $this->sit_is_validated = $validated;
    }

    public function setLineItemId($lineItemId) {
        $this->sit_lineitem_id = $lineItemId;
    }
    
    public function setState($state) {
        $this->sit_state = $state;
    }

    public function setValidationType($type) {
        $this->sit_validation_type = $type;
    }

    public function setHasToCategorizeOnAdserver() {
        $this->sit_categorized_on_adserver = '0';
    }

    public function setDomainList($domains) {
        $this->sit_domain_list = "";
        $domains = array_values($domains);
        $i = count($domains);
        foreach ($domains as $domain) {
            $last_iteration = !( --$i);
            if ($last_iteration)
                $this->sit_domain_list .= $domain;
            else
                $this->sit_domain_list .= $domain . "\n";
        }
    }

    /*     * *
     * @key el id del adserver
     */

    public function setKey($key) {
        $this->adserverKey = $key;
    }

    /*     * *
     * Relaciones con entidades
     */

    public function publisher() {
        return $this->belongsTo('Publisher', 'sit_publisher_id');
    }

    public function sections() {
        return $this->hasMany('Section', 'sct_site_id');
    }

    public function sectionsLikePlacements() {
        $placements = array();
        foreach ($this->sections as $section) {
            foreach ($section->getPlacements() as $placement) {
                $placements[] = $placement;
            }
        }
        return $placements;
    }

    public function placements() {
        return $this->hasMany('Placement', 'plc_site_id');
    }

    public function adservers() {
        return $this->belongsToMany('Adserver')->withPivot('adv_sit_adserver_key');
    }

    public function categories() {
        return $this->belongsToMany('Category');
    }

    public function imonomy() {
        return $this->hasOne('ImonomySite', 'site_id');
    }

    public function brid() {
        return $this->hasOne('BridSite', 'site_id');
    }

    /*     * *
     * Recibe &$site objeto Site por referencia
     * devuelve true si el publlisher aun no tiene dicho sitio
     * de lo contrario devuelve false y agrega un error.
     */

    private function errorIfPublisherAlredyHasSite(&$site, $name) {
        $exist = DB::table('sites')->where('sit_publisher_id', '=', $site->publisher->pbl_id)->where('sit_name', '=', $name)->count();
        if ($exist > 0) {
            // Add an error message
            $site->errors()->add('sit_name', Lang::get('validation.publisherAlredyHasSite', array('value' => $name)));
            return false;
        }
        return true;
    }

    /*     * *
     * Antes de validar
     * limpia la URL y valida que el publisher aun no tenga el mismo sitio.
     */

    public function beforeCreate() {
        $this->sit_name = cleanUrl($this->sit_name);
        return $this->errorIfPublisherAlredyHasSite($this, $this->sit_name);
    }

    public function createSiteInAdServer($adserverId = 0) {
        if ($adserverId == 0) {
            $adserverId = Session::get('adserver.id');
        }
        $adserverKey = Api::newSite($adserverId, $this);
        
        return $this->adservers()->save(Adserver::find($adserverId), array('adv_sit_adserver_key' => $adserverKey));
    }

    public function validateDomainList($dominios) {

        $arr_dominios = explode("\n", $dominios);
        foreach ($arr_dominios as $key => $domain) {
            $a = trim($domain);
            if (empty($a)) {
                unset($arr_dominios[$key]);
            }
        }
        foreach ($arr_dominios as &$domain) {
            $domain = cleanUrl($domain);
        }
        $arr_dominios = array_unique($arr_dominios);
        if (!in_array($this->getName(), $arr_dominios)) {
            $this->errors()->add('sit_name', Lang::get('validation.required_url', array('value' => $this->getName())));
        }

        if (sizeof($arr_dominios) > 1) {
            foreach ($arr_dominios as &$domain) {

                $validate = validate_domain_regular_expression($domain);
                if ($validate) {
                    if (!($this->getName() == $domain)) {
                        $this->errorIfPublisherAlredyHasSite($this, $domain);
                    }
                } else {
                    $this->errors()->add('sit_name', Lang::get('validation.curl_specific_url', array('value' => $domain)));
                }
            }
        } else {
            $this->errors()->add('sit_name', Lang::get('validation.itHasBeMoreThanOne'));
        }
        return $arr_dominios;
    }

}
