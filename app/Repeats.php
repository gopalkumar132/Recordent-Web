<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Repeats extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $guarded = ['id'];

   protected $fillable = [
        'notification_id', 'repeats','every_days','weekly_notification_days','monthly_date','ends_never',
        'ends_on','ends_after_occurrence'
    ];

}
