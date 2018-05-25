<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use LaravelBook\Ardent\Ardent;

class User extends Ardent implements UserInterface, RemindableInterface {

    use UserTrait,
        RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    public $autoHydrateEntityFromInput = true;
    public $forceEntityHydrationFromInput = true;
    protected $primaryKey = 'id';
    protected $guarded = array();
    protected $fillable = array('email', 'password', 'activation_code', 'reset_password_code', 'platform_id');
    
    public static $rules = array(
        'recaptcha_response_field' => 'required|recaptcha',
    );

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    public function profile() {
        return $this->hasOne('Profile', 'prf_user_id');
    }

    public function getEmail() {
        return $this->email;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getActivationCode() {
        return $this->activation_code;
    }
    
    public function publisher() {
        return $this->hasOne('Publisher', 'pbl_user_id');
    }
    
    public function administrator() {
        return $this->hasOne('Administrator', 'adm_user_id');
    }
    
    public function messages() {
        return $this->hasMany('Messages', 'msg_user_id');
    }
    
    public function adservers()
    {
        return $this->belongsToMany('Adserver')->withPivot('media_buyer_id');
    }
    
    public function platform() {
        return $this->belongsTo('Platform', 'platform_id');
    }
    
    public function getAdserver() {
        foreach ($this->adservers as $adserver) {
            return $adserver;
        }
        return NULL;
    }
    
    public function setAdserver($id, $media_buyer = NULL) {
        $adserver = Adserver::find($id);
        $adserver->users()->save($this, array('media_buyer_id' => $media_buyer));
    }
    
    public function setPlatform($id) {
        $this->platform()->associate(Platform::find($id));
    }
    
    public function isPublisher() {
        if ($this->publisher)
            return true;
        else
            return false;
    }
    
    public function isAdministrator() {
        if ($this->administrator)
            return true;
        else
            return false;
    }
    
    public function isActived() {
        if ($this->activated == 0)
            return false;
        else
            return true;
    }

}
