<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomIdsForBusinessStudentTempDuePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_due_payments', function (Blueprint $table) {
            $table->string('external_student_id')->nullable();
			$table->string('external_business_id')->nullable();
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
            $table->dropColumn('external_student_id');
			$table->dropColumn('external_business_id');
        });
    }
}
