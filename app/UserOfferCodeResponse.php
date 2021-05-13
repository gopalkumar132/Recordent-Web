<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOfferCodeResponse extends Model
{
    protected $fillable = [
        'user_id','offer_code_endpoint','response'];
    protected $guarded = ['id'];
}
