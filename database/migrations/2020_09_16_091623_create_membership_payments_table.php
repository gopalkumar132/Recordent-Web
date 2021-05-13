<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_id')->nullable();
            $table->integer('pricing_plan_id')->default(1);
            $table->enum('customer_type',['INDIVIDUAL','BUSINESS']);
            $table->float('payment_value')->default(0);
            $table->integer('customer_id')->default(0);
            $table->integer('added_by')->default(0);

            $table->text('payment_note')->nullable();
            $table->date('payment_date')->nullable();

            $table->integer('status')->default(0);
            $table->string('transaction_id')->nullable();
            $table->string('payment_mode')->nullable();
            $table->float('collection_fee_perc')->default(0);
            $table->float('collection_fee')->default(0);
            $table->float('gst_perc')->default(0);
            $table->float('gst_value')->default(0);
            $table->text('redirect_query_string')->nullable();
            $table->text('raw_response')->nullable();
            $table->float('total_collection_value')->default(0);
            
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
        Schema::dropIfExists('membership_payments');
    }
}
