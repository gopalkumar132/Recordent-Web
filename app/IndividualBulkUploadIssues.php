<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use General;


class IndividualBulkUploadIssues extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [
        'person_name','dob','father_name','mother_name','aadhar_number','contact_phone','added_by'
    ];*/
    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFatherNameAttribute($value)
    {
    	$value = strtolower($value);

    	$this->attributes['father_name'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setMotherNameAttribute($value)
    {
    	$value = strtolower($value);

    	$this->attributes['mother_name'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setAadharNumberAttribute($value)
    {
    	$value = strtolower($value);

    	$this->attributes['aadhar_number'] = $value==NULL ? NULL : General::encrypt($value);
    }
    
    public function setContactPhoneAttribute($value)
    {
    	$value = strtolower($value);

    	$this->attributes['contact_phone'] = $value==NULL ? NULL : General::encrypt($value);
    }
   

    public function setPersonNameAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['person_name'] = $value==NULL ? NULL : General::encrypt($value);
    }


    public function setDobAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['dob'] = $value==NULL ? NULL : General::encrypt($value);
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
    
    public function getFatherNameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getMotherNameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }  
    public function getAadharNumberAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }


    public function getPersonNameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getContactPhoneAttribute($value)
    {
    	if(!empty($value)){
    	}
    	return $value==NULL ? NULL : General::decrypt($value);
    }
	public function getDobAttribute($value){
        
		return $value==NULL ? NULL : General::decrypt($value);
	}
    public function getDobDmyAttribute($value){

        $newValue =  $value==NULL ? NULL : General::decrypt($value);
        if($newValue){
            $newValue = Carbon::parse($newValue);
            $newValue = $newValue->format('d/m/Y');
        }
        return $newValue;
    }
    public function getUniqueUrlCodeAttribute($value){
        
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getEmailAttribute($value){
        
        return $value==NULL ? NULL : General::decrypt($value);
    }

    

}
