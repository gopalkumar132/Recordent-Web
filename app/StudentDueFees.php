<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class StudentDueFees extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id','due_date','due_amount','due_note','deleted_at','delete_note','added_by','customer_no','invoice_no','proof_of_due','collection_date','grace_period', 'external_student_id','credit_period','invoice_date','balance_due'
    ];
    protected $guarded = ['id'];

    public function addedBy(){
    	return $this->belongsTo('App\User','added_by')->with('state','city','userType');
    }

    public function profile(){
        return $this->belongsTo('App\Students','student_id');
    }
	

    public function paid(){
        return $this->hasMany('App\StudentPaidFees','due_id');
    }

    public function dispute(){
        return $this->hasMany('App\Dispute','due_id')->with('reason');
    }
    
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
