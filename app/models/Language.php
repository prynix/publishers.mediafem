<?php

class Language extends Eloquent {
        protected $primaryKey = 'lng_id';
	protected $fillable = array('lng_short', 'lng_name');
	protected $guarded = array();

	public static $rules = array();
        
        /***
         * Getters
         */
        public function getShort() {
            return $this->lng_short;
        }
        
        public function getName() {
            return $this->lng_name;
        }
        
        public function getId() {
            return $this->lng_id;
        }
        
        public static function getLanguageByShort($short) {
            return self::where('lng_short', $short)->first();
        }
}
