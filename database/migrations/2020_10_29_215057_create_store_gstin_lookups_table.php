<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreGstinLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_gstin_lookups', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('user_id')->nullable();
            $table->string('gstin_no')->nullable();
            $table->text('gstin_response_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_gstin_lookups');
    }
}
