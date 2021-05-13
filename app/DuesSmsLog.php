<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;

class DuesSmsLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $table="dues_sms_log";


    public function customer(){
    	return $this->belongsTo('App\Students','customer_id');
    }
    
    
    public function business(){
    	return $this->belongsTo('App\Businesses','customer_id');
    }

    public function addedBy(){
        return $this->belongsTo('App\User','added_by');
    }

    public function setMessageAttribute($value)
    {
        // $value = strtolower($value);
        $this->attributes['message'] = $value==NULL ? NULL : General::encrypt($value);
    }

    public function setContactPhoneAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['contact_phone'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function getMessageAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    public function getContactPhoneAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }
    
}
