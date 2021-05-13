<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipPayment extends Model
{
    protected $guarded = ['id'];

    public function user(){
    	return $this->belongsTo('App\User','customer_id');
    }
    public function pricing_plan(){
    	return $this->belongsTo('App\PricingPlan','pricing_plan_id');
    }
}
