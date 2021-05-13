<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pricing_plan_id');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->float('membership_price')->default(0);
            $table->integer('free_customer_limit')->default(0);
            $table->integer('membership_payment_id')->nullable();
            $table->integer('customer_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membership_history');
    }
}
