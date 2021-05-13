<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempDuePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('temp_due_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_id', 191);
            $table->enum('customer_type', ['INDIVIDUAL', 'BUSINESS']);
            $table->integer('payment_value');
            $table->integer('due_id');
            $table->integer('customer_id');
            $table->integer('added_by');
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_due_payments');
    }
}
