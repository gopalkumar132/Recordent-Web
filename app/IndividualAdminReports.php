<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class IndividualAdminReports extends Model
{
    protected $table = 'individual_admin_reports';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_path', 'member_id'
    ];
}
