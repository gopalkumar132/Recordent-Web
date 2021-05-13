<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class DuePayment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $guarded = ['id'];
 

   public function individualProfile(){
       return $this->belongsTo('App\Students','customer_id');
   }
   public function businessProfile(){
       return $this->belongsTo('App\Businesses','customer_id');
   }

   public function individualDue(){
       return $this->belongsTo('App\StudentDueFees','due_id')->with('addedBy');
   }
   public function businessDue(){
       return $this->belongsTo('App\BusinessDueFees','due_id')->with('addedBy');
   }

   public function individualPaid(){
       return $this->belongsTo('App\StudentPaidFees','paid_id');
   }
   public function businessPaid(){
       return $this->belongsTo('App\BusinessPaidFees','paid_id');
   }

   public function frontIBProfile(){
       return $this->belongsTo('App\Individuals','added_by');
   }   





}
