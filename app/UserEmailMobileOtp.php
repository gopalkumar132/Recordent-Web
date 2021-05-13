<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;

class UserEmailMobileOtp extends Model
{
    protected $table = 'users_email_mobile_otp';

    protected $guarded = ['id'];
    public $timestamps = false;

    public function setMobileNumberAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['mobile_number'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setEmailAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['email'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setOtpAttribute($value)
    {
    	$value = strtolower($value);
    	$this->attributes['otp'] = $value==NULL ? NULL : General::encrypt($value);
    }


    public function getMobileNumberAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getEmailAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getOtpAttribute($value)
    {
    	return $value==NULL ? NULL : General::decrypt($value);
    }
    
}
