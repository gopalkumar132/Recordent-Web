<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class BusinessPaidFees extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $guarded = ['id'];
    public $timestamps = true;

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
