<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class UserPricingPlan extends Model
{

    protected $table = 'user_pricing_plan';
    protected $guarded = ['id'];
    // public $timestamps = false;

    public function plan(){
        return $this->belongsTo('App\PricingPlan','pricing_plan_id');
    }
    /*
    public function member(){
        return $this->belongsTo('App\User','user_id');
    }*/
    
    public function pricing_plan(){
        return $this->belongsTo('App\PricingPlan');
    }
    
    public function membership_payment(){
        return $this->belongsTo('App\MembershipPayment');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
}
