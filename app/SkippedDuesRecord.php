<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkippedDuesRecord extends Model
{
    protected $table = 'skipped_dues_record';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'request_data', 'total_skipped_record_count'
    ];
}
