<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Role extends Model
{

    public function getNameAttribute($value){
         return $value==NULL ? NULL : ucfirst($value);
    }

}
