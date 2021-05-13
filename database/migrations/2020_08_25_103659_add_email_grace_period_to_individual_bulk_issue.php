<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailGracePeriodToIndividualBulkIssue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('individual_bulk_upload_issues', function (Blueprint $table) {
            //$table->integer('grace_period')->default(0);
            //$table->text('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individual_bulk_upload_issues', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('grace_period');
        });
    }
}
