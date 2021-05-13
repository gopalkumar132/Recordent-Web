<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('template_id')->nullable();
            $table->integer('is_repeat')->nullable();
            $table->string('customer_type')->nullable();
            $table->dateTime('notification_date', 0)->nullable();
            $table->string('notification_start_time')->nullable();
            $table->string('notification_type')->nullable();
            $table->string('inclusions_amount_due')->nullable();
            $table->string('inclusions_status')->nullable();
            $table->dateTime('inclusions_start_date', 0)->nullable();
            $table->dateTime('inclusions_end_date', 0)->nullable();
            $table->string('exclusions_amount')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
