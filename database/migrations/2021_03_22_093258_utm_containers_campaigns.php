<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UtmContainersCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('utm_containers_campaigns', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->text('utm_campaign_url')->nullable();
        $table->string('utm_medium')->nullable();
        $table->string('utm_source')->nullable();
        $table->string('utm_id')->nullable();
        $table->string('utm_campaign')->nullable();
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
        Schema::dropIfExists('utm_containers_campaigns');
    }
}
