<?php

use LaravelBook\Ardent\Ardent;

class Messages extends Ardent {

    protected $table = 'messages';
    protected $primaryKey = 'msg_id';
    protected $fillable = array('msg_user_id', 'msg_subject', 'msg_from', 'msg_content', 'msg_view');
    protected $guarded = array();
    public static $rules = array();

    /*
     * Relaciones de entidades
     */

    public function user() {
        return $this->belongsTo('User', 'msg_user_id');
    }
    
    public static function getMessagesByUser($user_id) {
        return self::where('msg_user_id', $user_id)->get();
    }
    
    public static function getNotificationsByUser($user_id) {
        return self::where('msg_user_id', $user_id)->where('msg_view', '=', 1)->get();
    }

}
