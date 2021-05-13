<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class NotificationCustomerExclusion extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "notification_customer_exclusion";
   protected $guarded = ['id'];

   protected $fillable = [
        'notification_id', 'member_id','customer_id'
    ];

    public function member(){
        return $this->belongsTo('App\User','member_id');
    }
}
