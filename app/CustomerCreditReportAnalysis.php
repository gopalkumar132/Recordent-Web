<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerCreditReportAnalysis extends Model
{
    protected $table = 'customer_credit_report_analysis';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'type', 'customer_viewed'
    ];
}
