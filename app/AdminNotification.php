<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;

class AdminNotification extends Model
{
    protected $table = 'admin_notifications';

    protected $guarded = ['id'];
    public $timestamps = false;

    public function seen(){
    	return $this->hasMany('App\AdminNotificationSeen','admin_notification_id');
    }

    public function setTitleAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['title'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function getTitleAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    
}
