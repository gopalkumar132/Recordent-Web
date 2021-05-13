<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;
use Carbon\Carbon;

class ConsentPayment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'consent_payment';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function addedBy(){
        return $this->belongsTo('App\User','added_by')->with('state','city','userType');
    }

    
    public function setPersonNameAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['person_name'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setContactPhoneAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['contact_phone'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setUniqueIdentificationNumberAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['unique_identification_number'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setConcernedPersonPhoneAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['concerned_person_phone'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setBusinessNameAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['business_name'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = $value==NULL ? NULL : General::encrypt($value);
    }

    
    public function setCityAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['city'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setPinCodeAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['pincode'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setCompanyIdAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['company_id'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setAuthorizedSignatoryNameAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['authorized_signatory_name'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setAuthorizedSignatoryDesignationAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['authorized_signatory_designation'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setDirectorsEmailAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['directors_email'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setLinkContactPhoneAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['link_contact_phone'] = $value==NULL ? NULL : General::encrypt($value);
    }
    
    
    public function getPersonNameAttribute($value)
    {
        return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
    public function getContactPhoneAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getUniqueIdentificationNumberAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getConcernedPersonPhoneAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }

    public function getBusinessNameAttribute($value)
    {
        return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
    public function getAddressAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    
    public function getCityAttribute($value)
    {
        return $value==NULL ? NULL : ucfirst(General::decrypt($value));
    }
    public function getPinCodeAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }

    
    public function getCompanyIdAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }

    public function getAuthorizedSignatoryNameAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }

    public function getAuthorizedSignatoryDesignationAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }

    public function getDirectorsEmailAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }

    public function getLinkContactPhoneAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
}
    