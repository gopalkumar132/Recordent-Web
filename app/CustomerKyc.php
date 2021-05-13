<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;

class CustomerKyc extends Model
{
    
    public function setFirstnameAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['firstname'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setMiddlenameAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['middlename'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setLastnameAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['lastname'] = $value==NULL ? NULL : General::encrypt($value);
    }
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
    public function setDobAttribute($value)
    {
    	$value = strtolower($value);
    	if($value == '0000-00-00'){
    		$value = NULL;
    	}elseif($value==NULL){
    		$value = NULL;
    	}else{
    		$value = General::encrypt($value);
    	}
    	$this->attributes['dob'] = $value;
    }
    public function setAadharNumberAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['aadhar_number'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setMobileNumberAttribute($value)
    {
    	$value = strtolower($value);
		$this->attributes['mobile_number'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setPermenentAddressAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['permenent_address'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setIdProofNumberAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['id_proof_number'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setAddressProofNumberAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['address_proof_number'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setVehicleNameAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['vehicle_name'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setVehicleNumberAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['vehicle_number'] = $value==NULL ? NULL : General::encrypt($value);
    }

    
    public function getFirstnameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getMiddlenameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getLastnameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getFatherNameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getMotherNameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getDobAttribute($value){
        
		return $value==NULL ? NULL : General::decrypt($value);
	}
	public function getAadharNumberAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getMobileNumberAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getPermenentAddressAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getIdProofNumberAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getAddressProofNumberAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);    }
    public function getVehicleNameAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getVehicleNumberAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }

	    

}
