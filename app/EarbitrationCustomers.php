<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;


class EarbitrationCustomers extends Model
{

    protected $table = 'earbitration_customers';


    protected $fillable = [
        'member_id', 'customer_id', 'customer_type',
    ];

}
?>