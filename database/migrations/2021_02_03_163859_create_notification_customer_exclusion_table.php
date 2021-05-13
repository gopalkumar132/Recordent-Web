<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationCustomerExclusionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_customer_exclusion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('notification_id')->nullable();
            $table->bigInteger('member_id')->nullable();
            $table->text('customer_id')->nullable();
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
        Schema::dropIfExists('notification_customer_exclusion');
    }
}
