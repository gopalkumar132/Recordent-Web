<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;
use Carbon\Carbon;

class BusinessBulkUploadIssues extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    public $timestamps = false;

    public function setCompanyNameAttribute($value)
    {
    	$value = strtolower($value);

    	$this->attributes['company_name'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setUniqueIdentificationNumberAttribute($value)
    {
    	$value = strtolower($value);

    	$this->attributes['unique_identification_number'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setSectorNameAttribute($value)
    {
        $value = strtolower($value);

        $this->attributes['sector_name'] = $value==NULL ? NULL : General::encrypt($value);
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
    public function setStateAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['state'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setCityAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['city'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setUniqueUrlCodeAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['unique_url_code'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setEmailAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['email'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setBusinessTypeAttribute($value)
    {
        $value = strtolower($value);

        $this->attributes['business_type'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function getCompanyNameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }

    public function getUniqueIdentificationNumberAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getConcernedPersonNameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
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
    public function getStateAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getCityAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getSectorNameAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getUniqueUrlCodeAttribute($value){
        
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getEmailAttribute($value){
        
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getBusinessTypeAttribute($value){
        
        return $value==NULL ? NULL : General::decrypt($value);
    }

    
}
