<?php
use LaravelBook\Ardent\Ardent;
class Currency extends Ardent {

	public $forceEntityHydrationFromInput = false;
        public $autoHydrateEntityFromInput = false;

        protected $primaryKey = 'crr_id';
	protected $fillable = array('crr_money', 'crr_one_dollar');
	protected $guarded = array();

	public static $rules = array(
		'crr_money' => 'required',
		'crr_one_dollar' => 'required|numeric'
	);

}
