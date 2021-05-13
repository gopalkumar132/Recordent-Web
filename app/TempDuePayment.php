<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TempDuePayment extends Model
{

    /**
     * This model will be used only when member do payment behalf of customer.
     * we are storing customer type, due id, collection fees with gst, and payment value  when member initiate the due payment
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $guarded = ['id'];

}
