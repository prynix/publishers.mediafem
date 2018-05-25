<?php

use LaravelBook\Ardent\Ardent;

/**
 * ORM Grupo.
 * 
 * Campos 
 ** grp_id
 ** grp_name
 ** grp_description
 ** grp_is_default
 ** created_at
 ** updated_at
 * 
 */
class Group extends Ardent {
    
    protected $primaryKey = 'grp_id';
    public $forceEntityHydrationFromInput = false;
    public $autoHydrateEntityFromInput = false;
    protected $fillable = array();
    protected $guarded = array();
    public static $rules = array();
    
    /**
     * Id
     * @return int
     */
    public function getId() {
        return $this->grp_id;
    }
    
    /**
     * Nombre
     * @return string
     */
    public function getName() {
        return $this->grp_name;
    }
    
    /**
     * Descripción
     * @return string
     */
    public function getDescription() {
        return $this->grp_description;
    }
    
    /**
     * 
     * ORM Usuarios
     */
    public function administrators() {
        return $this->hasMany('Administrator', 'adm_group_id');
    }
    
    /**
    * ORM Permisos
    */
    public function permissions() {
        return $this->belongsToMany('Permission');
    }
    
    /**
     * El grupo tiene el permiso
     * @param string $tab
     * @return boolean
     */
    public function has($key) {        
        foreach ($this->permissions as $permission) {
            if (preg_match("/^".$key."/", $permission->getTab())){
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * Devuelve un array de permisos encriptados
     * @return array
     */
    public function getEncryptedPermissions() {
       $permissions = array();
       foreach ($this->permissions as $permission) {
           $permissions[] = Crypt::encrypt($permission->getTab());
       }
       return $permissions;
    }
    
    /**
     * Grupo por defecto
     * @return Group
     */
    public static function getDefault() {
        return Group::where('grp_is_default', '1')->get();
    }
}

