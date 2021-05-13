<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class NotificationType extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $table = "notification_type";
   protected $guarded = ['id'];

   protected $fillable = [
        'name', 'status'
    ];

}
