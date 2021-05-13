<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class UserType extends Model
{
    public function role(){
    	return $this->belongsTo('TCG\Voyager\Models\Role', 'role_id');
    }

    public function getNameAttribute($value){
         return $value==NULL ? NULL : ucfirst($value);
    }

}
