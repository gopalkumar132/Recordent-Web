<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class StudentPaidFees extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id','paid_date','paid_amount','paid_note','due_id','deleted_at','delete_note','added_by','payment_done_by','payment_done_by_id', 'external_student_id','payment_options_drop_down','payment_waved_off_amount','payment_options_external_id'
    ];
    protected $guarded = ['id'];


    public function setExternalStudentIdAttribute($value)
    {
        if(isset($value)) { $value = strtoupper($value); }
        $this->attributes['external_student_id'] = $value;
    }

    public function getExternalStudentIdAttribute($value)
    {
    	$value = strtoupper($value);
        return $value==NULL ? NULL : $value;
    }
}
