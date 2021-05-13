<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;
use Carbon\Carbon;

class Businesses extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    public $timestamps = false;

    public function dues(){
        return $this->hasMany('\App\BusinessDueFees','business_id');
    }

    public function setCompanyNameAttribute($value)
    {
    	$value = strtolower($value);

    	$this->attributes['company_name'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setUniqueIdentificationNumberAttribute($value)
    {
    	$value = strtoupper($value);

    	$this->attributes['unique_identification_number'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setConcernedPersonNameAttribute($value)
    {
    	$value = strtolower($value);

    	$this->attributes['concerned_person_name'] = $value==NULL ? NULL : General::encrypt($value);
    }
    
    public function setConcernedPersonPhoneAttribute($value)
    {
    	$value = strtolower($value);

    	$this->attributes['concerned_person_phone'] = $value==NULL ? NULL : General::encrypt($value);
    }
   

    public function setConcernedPersonAlternatePhoneAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['concerned_person_alternate_phone'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setAddressAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['address'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setEmailAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['email'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function getCompanyNameAttribute($value)
    {
    	return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }

    public function getUniqueIdentificationNumberAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getConcernedPersonNameAttribute($value)
    {
    	return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
    public function getConcernedPersonPhoneAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getConcernedPersonAlternatePhoneAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getAddressAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getBaddressAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getBnameAttribute($value)
    {
        return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
    public function getEmailAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    
}
