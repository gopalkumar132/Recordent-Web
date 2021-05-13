<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMakePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('make_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->integer('customer_id');
            $table->string('customer_type')->nullable();
            $table->string('customer_mobile_no')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('unique_url_code')->nullable();
            $table->float('payment_value')->default(0);
            $table->float('gst_value')->default(0);
            $table->tinyInteger('status')->comment('1= Initiated, 2=pending, 3=aborted, 4=success, 5= failed');
            $table->float('total_collection_value')->default(0);
            $table->float('payment_mode')->nullable();
            $table->integer('added_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('make_payment');
    }
}
