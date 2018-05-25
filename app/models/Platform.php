<?php

class Platform extends Eloquent {
    protected $primaryKey = 'plt_id';
    protected $fillable = array();
    protected $guarded = array();
    public static $rules = array();

    public function id() {
        return $this->plt_id;
    }
    public function name() {
        return $this->plt_name;
    }
    public function brand() {
        return $this->plt_brand;
    }
    public function logo() {
        return $this->plt_logo;
    }
    public function color1() {
        return $this->plt_color1;
    }
    public function favicon() {
        return $this->plt_favicon;
    }
    public function short() {
        return $this->plt_short;
    }


    /*     * *
     * Relationships
     */

}
