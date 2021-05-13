<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Campaign extends Model
{
   protected $fillable = [
         'email','user_type','email_sent_at','promotion_type'];
    public $timestamps = true;
}
