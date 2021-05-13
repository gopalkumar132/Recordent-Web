<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersOfferCodes extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','offer_code','offer_code_status','response'];
    protected $guarded = ['id'];
}
