<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusToDefaultZero extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dues_sms_log', function (Blueprint $table) {
           $table->smallInteger('status')->tinyInteger('status')->default(0)->comment('0- pending, 1- sent, 2- fail')->change();
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
            $table->tinyInteger('status')->comment('1- sent, 2- fail')->change();
        });
    }
}
