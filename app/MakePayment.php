<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;
use Carbon\Carbon;

class MakePayment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'make_payment';
    protected $guarded = ['id'];

    public function addedBy(){
        return $this->belongsTo('App\User','added_by')->with('state','city','userType');
    }
    
    public function setCustomerMobileNoAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['customer_mobile_no'] = $value==NULL ? NULL : General::encrypt($value);
    }    
    
    public function getCustomerMobileNoAttribute($value)
    {
        return $value==NULL ? NULL : General::decrypt($value);
    }

    public function user(){
        return $this->belongsTo('App\User','customer_id');
    }
}
    