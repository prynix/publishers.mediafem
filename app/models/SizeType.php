<?php

use LaravelBook\Ardent\Ardent;
class SizeType extends Ardent {
	protected $table = 'size_types';
        protected $primaryKey = 'siz_typ_id';
	protected $fillable = array('siz_typ_name');
	protected $guarded = array();

	public static $rules = array();
	
        /***
         * Getters
         */
        public function getName() {
            return $this->siz_typ_name;
        }
        
        /***
         * Relationships
         */
	public function sizes()
	{
		return $this->hasMany('Size');
	}
}
