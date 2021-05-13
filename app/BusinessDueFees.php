<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class BusinessDueFees extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    public $timestamps = false;

    public function addedBy(){
    	return $this->belongsTo('App\User','added_by')->with('state','city','userType');
    }

     public function profile(){
        return $this->belongsTo('App\Businesses','business_id');
    }

    public function paid(){
        return $this->hasMany('App\BusinessPaidFees','due_id');
    }
    public function dispute(){
        return $this->hasMany('App\Dispute','due_id')->with('reason');
    }

    public function setExternalBusinessIdAttribute($value)
    {
        if(isset($value)) { $value = strtoupper($value); }
        $this->attributes['external_business_id'] = $value;
    }

    public function getExternalBusinessIdAttribute($value)
    {
        $value = strtoupper($value);
        return $value==NULL ? NULL : $value;
    }
    
}
