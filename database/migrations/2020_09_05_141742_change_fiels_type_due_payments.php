<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFielsTypeDuePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('due_payments', function (Blueprint $table) {

           $table->decimal('collection_fee',10,2)->default(0)->change();
           $table->decimal('gst_value',10,2)->default(0)->change();
           $table->decimal('total_collection_value',10,2)->default(0);
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('due_payments', function (Blueprint $table) {
            $table->integer('collection_fee')->default(0)->change();
            $table->integer('gst_value')->default(0)->change();
            $table->drop('total_collection_value');
        });
    }
}
