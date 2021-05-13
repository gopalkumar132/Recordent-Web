<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempCampaignEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_campaign_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email','191');
            $table->string('name','191');
            $table->text('email_subject')->nullable();
            $table->text('email_content')->nullable();
            $table->tinyInteger('user_type')->default(0)->comment('0-User, 1-Individual, 2-Business');
            $table->string('campaign_type', '191')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_campaign_emails');
    }
}
