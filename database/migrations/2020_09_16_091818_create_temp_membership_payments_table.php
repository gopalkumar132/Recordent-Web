<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempMembershipPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_membership_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_id')->nullable();
            $table->integer('pricing_plan_id')->default(1);
            $table->enum('customer_type',['INDIVIDUAL','BUSINESS']);
            $table->float('payment_value')->default(0);
            $table->integer('customer_id')->default(0);
            $table->integer('added_by')->default(0);
            $table->text('payment_note')->nullable();
            $table->date('payment_date')->nullable();
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
        Schema::dropIfExists('temp_membership_payments');
    }
}
