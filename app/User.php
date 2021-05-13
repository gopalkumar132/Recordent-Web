<?php

namespace App;

use Illuminate\Notifications\Notifiable;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\MustSendEmail;
use App\Traits\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use General;
use Log;
class User extends \TCG\Voyager\Models\User
{
    use Notifiable,MustSendEmail,MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','mobile_number','business_name','branch_name','address','role_id','status','user_type','country_code','pincode','country_id','city_id','state_id','type_of_business','email_sent_at','otp','gstin_udise','mobile_verified_at','company_type','business_short','reports_individual','reports_business','collection_fee_individual','collection_fee_business'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_sent_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'mobile_verified_at'=>'datetime',
    ];


    public function city(){
        return $this->belongsTo('App\City','city_id');
    }

    public function state(){
        return $this->belongsTo('App\State','state_id');
    }

    public function userType(){
        return $this->belongsTo('App\UserType','user_type');
    }
   /* public function plans(){
        //return $this->belongsToMany('App\PricingPlan', 'user_pricing_plan','user_id','pricing_plan_id');
        //return $this->hasMany('App\UserPricingPlan','user_id');
    }*/

    public function setNameAttribute($value){
        //$value = strtolower($value);
        $value = strtoUpper($value);
        $this->attributes['name'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setEmailAttribute($value){
        $value = strtolower($value);
        $this->attributes['email'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setMobileNumberAttribute($value){
        $value = strtolower($value);
        $this->attributes['mobile_number'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setBusinessNameAttribute($value){
        //$value = strtolower($value);
		$value = strtoUpper($value);
        $this->attributes['business_name'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setBranchNameAttribute($value){
        $value = strtolower($value);
        $this->attributes['branch_name'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setAddressAttribute($value){
        //$value = strtolower($value);
		$value = strtoUpper($value);
        $this->attributes['address'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setTypeOfBusinessAttribute($value){
        $value = strtolower($value);
        $this->attributes['type_of_business'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setOtpAttribute($value){
        $value = strtolower($value);
        $this->attributes['otp'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function setGstinUdiseAttribute($value){
        //$value = strtolower($value);
		$value = strtoUpper($value);
        $this->attributes['gstin_udise'] = $value==NULL ? NULL : General::encrypt($value);
    }
      public function setBusinessShortAttribute($value){
        $value = strtoupper($value);
        $this->attributes['business_short'] = $value==NULL ? NULL : General::encrypt($value);
    }
    
     

    public function getNameAttribute($value){
        return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
    public function getEmailAttribute($value){
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getMobileNumberAttribute($value){
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getBusinessNameAttribute($value){
        return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
    public function getBnameAttribute($value){
        return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
    public function getBranchNameAttribute($value){
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getAddressAttribute($value){
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getTypeOfBusinessAttribute($value){
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getOtpAttribute($value){
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getGstinUdiseAttribute($value){
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getContactPersonAttribute($value){
        return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
	public function getBusinessShortAttribute($value){
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getAccountNumberAttribute($value){
        return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
    public function getIfscCodeAttribute($value){
        return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
    public function getAccountHolderNameAttribute($value){
        return $value==NULL ? NULL : strtoupper(General::decrypt($value));
    }
    public function user_pricing_plan(){
        return $this->hasOne('App\UserPricingPlan');
    }

    public function invoices(){
        return $this->hasMany('App\MembershipPayment','customer_id')->where('status',4);
    }

    public function get_member_previous_plans(){
        return $this->hasMany('App\MembershipHistory','customer_id')->orderBy('id', 'DESC');
    }
}
