<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CustomerType extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $table = "customer_type";
   protected $guarded = ['id'];

   protected $fillable = [
        'name', 'status'
    ];

}
