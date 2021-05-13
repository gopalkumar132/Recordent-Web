<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGracePeriodToStudentDueFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_due_fees', function (Blueprint $table) {
            //$table->integer('grace_period')->default(0);
            //$table->date('collection_date')->nullable();
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
            $table->dropColumn('email');
            $table->dropColumn('collection_date');
        });
    }
}
