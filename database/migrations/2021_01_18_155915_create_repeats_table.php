<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repeats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('notification_id')->nullable();
            $table->string('repeats')->nullable();
            $table->integer('every_days')->nullable();
            $table->text('weekly_notification_days')->nullable();
            $table->dateTime('monthly_date', 0)->nullable();
            $table->integer('ends_never')->nullable();
            $table->dateTime('ends_on', 0)->nullable();
            $table->integer('ends_after_occurrence')->nullable();
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
        Schema::dropIfExists('repeats');
    }
}
