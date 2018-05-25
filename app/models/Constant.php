<?php

class Constant extends Eloquent {
    protected $primaryKey = 'cns_id';
    protected $fillable = array();
    protected $guarded = array();
    public static $rules = array();

    public static function getValue($key) {
        return self::where('cns_key', $key)->first();
    }

    public static function value($key) {
        $constant = self::where('cns_key', $key)->first();
        return $constant->cns_value;
    }

    public function setValue($value) {
        $this->cns_value = $value;
    }

    /*     * *
     * Relationships
     */

    public function group() {
        return $this->belongsTo('ConstantGroup', 'cns_constant_group_id', 'cns_grp_id');
    }

}
