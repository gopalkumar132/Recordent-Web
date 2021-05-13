<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApproveRejectStatusAndApproveRejectDatetime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dues_sms_log', function (Blueprint $table) {
            $table->tinyInteger('approve_reject_status')->default(0)->comment('0- approval pending, 1- approved, 2- rejected');
            $table->dateTime('approve_reject_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dues_sms_log', function (Blueprint $table) {
            $table->dropColumn('approve_reject_status');
            $table->dropColumn('approve_reject_at');
        });
    }
}
