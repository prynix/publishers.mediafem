<?php

use LaravelBook\Ardent\Ardent;

class MessagesGroupsDefault extends Ardent {

    protected $table = 'messages_groups_default';
    protected $primaryKey = 'msgdg_id';
    protected $fillable = array('msgdg_name');
    protected $guarded = array();
    public static $rules = array();
}
