<?php

class Mail extends Eloquent {
        protected $primaryKey = 'mls_id';
	protected $fillable = array('mls_name', 'mls_host', 'mls_username', 'mls_password');
	protected $guarded = array();

	public static $rules = array();
}
