<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Templates extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $guarded = ['id'];

   protected $fillable = [
        'name', 'content','type'
    ];

}
