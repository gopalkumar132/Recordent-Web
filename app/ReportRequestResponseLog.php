<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportRequestResponseLog extends Model
{
    protected $table = 'report_request_response_log';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'request_data', 'response_data','request_params','status'
    ];
}
