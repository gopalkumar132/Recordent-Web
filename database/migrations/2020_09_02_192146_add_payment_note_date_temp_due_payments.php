<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentNoteDateTempDuePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_due_payments', function (Blueprint $table) {
            //$table->text('payment_note')->nullable();
            //$table->date('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_due_payments', function (Blueprint $table) {
            $table->drop('payment_note');
            $table->drop('payment_date');
            
        });
    }
}
