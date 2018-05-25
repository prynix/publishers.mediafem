<?php

use LaravelBook\Ardent\Ardent;
class Category extends Ardent {
        protected $primaryKey = 'ctg_id';
	protected $fillable = array('ctg_name', 'ctg_exclude');
	protected $guarded = array();

	public static $rules = array();
	
	public function adservers()
        {
            return $this->belongsToMany('Adserver')->withPivot('adv_ctg_adserver_key', 'adv_ctg_is_active', 'adv_ctg_is_default');
        }
	
	public function sites()
        {
            return $this->belongsToMany('Site', 'ctg_site_id');
        }
        
        public function getName() {
            return $this->ctg_name;
        }
        
        public function getId() {
            return $this->ctg_id;
        }
        
        public function setName($name) {
            $this->ctg_name = $name;
        }
        
        /***
        * Devuelve el id del sitio en $idAdserver adserver
        * En caso de no estar registrado devuelve null
        */
        public function getAdserverKey($idAdserver) {
            $list_of_categories_adserver = null;
            foreach ($this->adservers as $key) {
                if(($key->pivot->adserver_id == $idAdserver) && ($key->pivot->adv_ctg_is_active == 1))
                    $list_of_categories_adserver[] = $key->pivot->adv_ctg_adserver_key;
            }
            return $list_of_categories_adserver;
        }
        
        public function isDefaultInAdserver($idAdserver) {
            foreach ($this->adservers as $key) {
                if(($key->pivot->adserver_id == $idAdserver) && ($key->pivot->adv_ctg_is_default == 1))
                    return true;
            }
            return false;
        }
        
        public static function getAdserverDefaultCategories($idAdserver) {
            $defaultCategories = array();
            $categories = Category::all();
            foreach ($categories as $category) {
                if ($category->isDefaultInAdserver($idAdserver))
                    $defaultCategories[] = $category;
            }
            
            return $defaultCategories;
        }
        
        public static function getAdserverNotDefaultCategories($idAdserver) {
            $notDefaultCategories = array();
            $categories = Category::orderBy('ctg_name')->get();
            foreach ($categories as $category) {
                if (!$category->isDefaultInAdserver($idAdserver) && $category->getAdserverKey($idAdserver))
                    $notDefaultCategories[] = $category;
            }
            
            return $notDefaultCategories;
        }
        
        public static function getAll($adserverId) {
            return DB::table('adserver_category')
                    ->where('adserver_category.adserver_id', $adserverId)
                    ->select('adserver_category.category_id')
                    ->get();
        }
        
        public static function getBySites($listSiteIds) {
            return DB::table('category_site')
                    ->whereIn('site_id', $listSiteIds)
                    ->select('category_id')
                    ->groupBy('category_id')
                    ->get();
        }
}
