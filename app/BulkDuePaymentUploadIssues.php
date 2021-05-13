<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use General;
use Carbon\Carbon;

class BulkDuePaymentUploadIssues extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    public $timestamps = false;

    public function setUniqueUrlCodeAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['unique_url_code'] = $value==NULL ? NULL : General::encrypt($value);
    }
    public function getUniqueUrlCodeAttribute($value){        
        return $value==NULL ? NULL : General::decrypt($value);
    }

    
}
