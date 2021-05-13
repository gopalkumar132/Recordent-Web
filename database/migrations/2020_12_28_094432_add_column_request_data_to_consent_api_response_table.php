<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnRequestDataToConsentApiResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consent_api_response', function (Blueprint $table) {
            $table->text('request_data')->nullable();
            $table->string('status')->nullable();
            $table->string('ip_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consent_api_response', function (Blueprint $table) {
            $table->dropColumn(['request_data', 'status', 'ip_address']);
        });
    }
}
