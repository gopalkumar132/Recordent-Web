<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreditPeriodToStudentDueFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_due_fees', function (Blueprint $table) {
            $table->integer('credit_period')->default(0);
            $table->date('invoice_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_due_fees', function (Blueprint $table) {
            $table->dropColumn('credit_period');
            $table->dropColumn('invoice_date');
        });
    }
}
