<?php

class Alert extends Eloquent {
	protected $fillable = array('message');
	protected $guarded = array();
        protected $primaryKey = 'alr_id';

	public static $rules = array();
	
	public function publisher()
        {
            return $this->belongsTo('Publisher', 'alr_publisher_id');
        }
}
