<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;
use Carbon\Carbon;

class ConsentRequestDetail extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'consent_request_detail';
    protected $guarded = ['id'];
    public $timestamps = false;

    
    public function consentRequest(){
        return $this->belongsTo('App\ConsentRequest','consent_request_id');
    }

   

}
