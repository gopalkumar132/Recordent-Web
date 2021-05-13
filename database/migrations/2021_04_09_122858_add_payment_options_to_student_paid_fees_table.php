<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentOptionsToStudentPaidFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_paid_fees', function (Blueprint $table) {
            $table->string('payment_options_drop_down')->nullable();
            $table->string('payment_options_external_id')->nullable();
            $table->string('payment_waved_off_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_paid_fees', function (Blueprint $table) {
            $table->dropColumn('payment_options_drop_down');
            $table->dropColumn('payment_options_external_id');
            $table->dropColumn('payment_waved_off_amount');
        });
    }
}
