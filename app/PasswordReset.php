<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;

class PasswordReset extends Model
{
    protected $table = 'password_resets';

    protected $guarded = ['id'];
    // public $timestamps = false;

    

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
