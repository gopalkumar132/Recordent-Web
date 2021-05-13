<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreGstinLookups extends Model
{
    protected $fillable = [
        'user_id', 'gstin_no', 'gstin_response_data'
		];
}
