<?php

use LaravelBook\Ardent\Ardent;

class MessagesDefault extends Ardent {

    protected $table = 'messages_default';
    protected $primaryKey = 'msgd_id';
    protected $fillable = array('msgd_group', 'msgd_subject', 'msgd_from', 'msgd_content', 'msgd_language_id');
    protected $guarded = array();
    public static $rules = array();

    /*
     * GETs
     */

    public static function lastGroup() {
        $ult = MessagesDefault::orderby('msgd_group', 'desc')->first();

        if ($ult)
            return $ult->msgd_group;

        return 0;
    }    
    
    public static function getMessageByGroupAndLanguage($group_id, $language) {
        return self::where('msgd_group', $group_id)->where('msgd_language_id', $language)->first();
    }

}
