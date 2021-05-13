<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnRequestDataTableSkippedDuesRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skipped_dues_record', function (Blueprint $table) {
            $table->longText('request_data')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skipped_dues_record', function (Blueprint $table) {
            $table->text('request_data')->nullable();
        });
    }
}
