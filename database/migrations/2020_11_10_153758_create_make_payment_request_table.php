<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMakePaymentRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('make_payment_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('customer_mobile_no')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('unique_url_code')->nullable();
            $table->float('payment_value')->default(0);
            $table->float('gst_value')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->float('total_collection_value')->default(0);
            $table->integer('added_by')->default(0);
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
        Schema::dropIfExists('make_payment_request');
    }
}
