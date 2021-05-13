<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsentAPIResponse extends Model
{
    protected $table = 'consent_api_response';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function consentRequest(){
        return $this->belongsTo('App\ConsentRequest','consent_request_id');
    }
}
