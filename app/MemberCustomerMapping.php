<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class MemberCustomerMapping extends Model
{
    protected $guarded = ['id'];
    protected $table = 'member_customer_id_mapping';
    public $timestamps = false;

    protected $fillable = [
        'member_id', 'customer_id', 'customer_type',
    ];

    public function user(){
    	return $this->belongsTo('App\User','member_id');
    }

    public function individualCustomer(){
    	return $this->belongsTo('App\Students','customer_id');
    }

    public function businessCustomer(){
    	return $this->belongsTo('App\Businesses','customer_id');
    }
}