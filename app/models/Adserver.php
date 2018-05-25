<?php
use LaravelBook\Ardent\Ardent;
class Adserver extends Ardent {
	protected $fillable = array('adv_name', 'adv_user', 'adv_password', 'adv_class_name', 'adv_is_default', 'adv_token', 'adv_minutes_to_expire_token', 'adv_token_set');
	protected $guarded = array();
        protected $primaryKey = 'adv_id';
        
	public static $rules = array();
	
        /***
         * Getters
         */
        public function getId() {
            return $this->adv_id;
        }
        public function getUsername() {
            return $this->adv_user;
        }

        public function getPassword() {
            return $this->adv_password;
        }
        
        public function getName() {
            return $this->adv_name;
        }
        public function getClassName() {
            return $this->adv_class_name;
        }

        public function getToken() {
            return $this->adv_token;
        }
        
        public function getMinutesToExpireToken() {
            return $this->adv_minutes_to_expire_token;
        }
        
        public function getTokenSet() {
            return $this->adv_token_set;
        }
                
        public static function getDefault() {
            return self::where('adv_is_default', '1')->first();
        }
        
        public function minutesOfLastSetToken() {
            //return DB::raw(' where adservers.adv_id = ? limit 1;')
            return DB::table('adservers')->select(DB::raw('TIMESTAMPDIFF(MINUTE, adv_token_set, NOW()) as minutes'))
                    ->where('adv_id', $this->adv_id)->first();
        }

        /***
         * Setters
         */
        public function setTimeStampOfToken() {
            $this->adv_token_set = date("Y-m-d H:i:s");
        }
        
        public function setTimeStampOfTokenFixed($datetime) {
            $this->adv_token_set = date("Y-m-d H:i:s", strtotime($datetime));
        }
        
        public function setToken($token) {
            $this->adv_token = $token;
        }
        
        /***
         * Relationships
         */
        public function publishers()
        {
            return $this->belongsToMany('Publisher')->withPivot('adv_pbl_adserver_key');
        }
	
	public function sites()
        {
            return $this->belongsToMany('Site')->withPivot('adv_sit_adserver_key');
        }
        
	public function users()
        {
            return $this->belongsToMany('User')->withPivot('media_buyer_id');
        }
	
	public function sizes()
        {
            return $this->belongsToMany('Size')->withPivot('adv_siz_is_active', 'adv_siz_value');
        }
	
	public function categories()
        {
            return $this->belongsToMany('Category')->withPivot('adv_ctg_adserver_key', 'adv_ctg_is_active', 'adv_ctg_is_default');
        }
    
        public function fields()
        {
            return $this->belongsToMany('Field')->withPivot('adv_fld_name');
        }
        
        public function administrators()
        {
            return $this->belongsToMany('Administrator')->withPivot('adm_adv_adserver_key');
        }
        
        public function getActiveSizes() {
            $activeSizes = array();
            foreach ($this->sizes as $size) {
                if ($size->isActive())
                    $activeSizes[] = $size;
            }
            return $activeSizes;
        }
}
