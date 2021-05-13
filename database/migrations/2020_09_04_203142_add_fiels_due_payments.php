<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFielsDuePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('due_payments', function (Blueprint $table) {
            $table->enum('payment_done_by', ['ADMIN_MEMBER','CUSTOMER'])->default('CUSTOMER');
            $table->integer('collection_fee_perc')->default(0);
            $table->integer('collection_fee')->default(0);
            $table->integer('gst_perc')->default(0);
            $table->integer('gst_value')->default(0);
            $table->text('redirect_query_string')->nallable();
            

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
            $table->drop('payment_done_by');
            $table->drop('collection_fee_perc');
            $table->drop('collection_fee');
            $table->drop('gst_perc');
            $table->drop('gst_value');
            $table->drop('redirect_query_string');
        });
    }
}
