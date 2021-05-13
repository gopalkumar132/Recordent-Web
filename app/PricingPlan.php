<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PricingPlan extends Model
{
    protected $table = 'pricing_plans';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function subPlanId(){
    	return $this->belongsTo('App\PricingPlan','id');
    }
}
