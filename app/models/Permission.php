<?php

use LaravelBook\Ardent\Ardent;

/**
 * ORM Permiso.
 * 
 * Campos 
 ** prm_tab
 ** prm_description
 ** created_at
 ** updated_at
 * 
 */
class Permission extends Ardent {
    
    protected $primaryKey = 'prm_tab';
    public $forceEntityHydrationFromInput = false;
    public $autoHydrateEntityFromInput = false;
    protected $fillable = array();
    protected $guarded = array();
    public static $rules = array();
    
    /**
     * Solapa
     * @return string
     */
    public function getTab() {
        return $this->prm_tab;
    }
    
    /**
     * Descripción
     * @return string
     */
    public function getDescription() {
        return $this->prm_description;
    }
    
}

