<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;

class Individuals extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile_number','udise_gstn','otp','created_at','updated_at','status','email',
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function setMobileNumberAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['mobile_number'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setUdiseGstnAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['udise_gstn'] = $value==NULL ? NULL : General::encrypt($value);
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

    public function getUdiseGstnAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }

    public function getOtpAttribute($value){
        return $value==NULL ? NULL : General::decrypt($value);
    }

    public function setEmailAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['email'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function getEmailAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }

}
