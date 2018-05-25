<?php

class ConstantGroup extends Eloquent {
        protected $primaryKey = 'cns_grp_id';
        
        protected $table = 'constant_groups';
	protected $fillable = array();
	protected $guarded = array();

	public static $rules = array();
        
        public function getName() {
            return $this->cns_grp_name;
        }
        
        /***
         * Relationships
         */
	public function constants()
	{
		return $this->hasMany('Constant', 'cns_constant_group_id');
	}
}
