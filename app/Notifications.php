<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Notifications extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $table = "notifications";
   protected $guarded = ['id'];

   protected $fillable = [
        'user_id', 'template_id','is_repeat','customer_type','notification_date','notification_start_time',
        'notification_type','inclusions_amount_due','inclusions_status','inclusions_start_date','inclusions_end_date',
        'exclusions_amount'
    ];

    public function customer(){
        return $this->belongsTo('App\CustomerType','customer_type');
    }

    public function notification(){
        return $this->belongsTo('App\NotificationType','notification_type');
    }

    public function customer_exclusion(){
        return $this->hasMany('App\NotificationCustomerExclusion','notification_id');
    }


}
