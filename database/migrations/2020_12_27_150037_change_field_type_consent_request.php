<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldTypeConsentRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consent_request', function (Blueprint $table) {

           $table->integer('idtype')->nullable()->change();
           $table->integer('report')->nullable()->change();
           
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

           $table->integer('report')->nullable()->change();
           $table->integer('report')->nullable()->change();
           
        });
    }
}
