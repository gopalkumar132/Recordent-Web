<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipHistory extends Model
{
    protected $guarded = ['id'];
    protected $table = 'membership_history';
    public $timestamps = false;

    protected $fillable = [
        'pricing_plan_id', 'start_date', 'end_date', 'membership_payment_id', 'membership_price', 'customer_id', 'free_customer_limit'
    ];

    public function user(){
    	return $this->belongsTo('App\User','customer_id');
    }

    public function membership_payment(){
    	return $this->belongsTo('App\MembershipPayment','membership_payment_id');
    }

    public function pricing_plan(){
    	return $this->belongsTo('App\PricingPlan','pricing_plan_id');
    }
}
