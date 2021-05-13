<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndividualAdminReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_admin_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_path')->nullable();
            $table->string('member_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_dt')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('individual_admin_reports');
    }
}
