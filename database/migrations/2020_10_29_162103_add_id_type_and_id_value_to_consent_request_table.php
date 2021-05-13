<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdTypeAndIdValueToConsentRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consent_request', function (Blueprint $table) {
            $table->text('idtype')->nullable();
            $table->text('idvalue')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consent_request', function (Blueprint $table) {
            $table->text('idtype')->nullable();
            $table->text('idvalue')->nullable();
        });
    }
}
