<?php

class Field extends Eloquent {
        protected $primaryKey = 'fld_id';
        protected $fillable = array('fld_name');
	protected $guarded = array();
        public static $rules = array();
        
        public function adservers()
        {
            return $this->belongsToMany('Adserver')->withPivot('adv_fld_name');
        }
	
}
