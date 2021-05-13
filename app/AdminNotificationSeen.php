<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class AdminNotificationSeen extends Model
{
    protected $table = 'admin_notifications_seen';

    protected $guarded = ['id'];
    public $timestamps = false;
    
}
