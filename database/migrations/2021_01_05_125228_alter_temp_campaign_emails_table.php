<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTempCampaignEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_campaign_emails', function (Blueprint $table) {
            $table->dropColumn('email_subject');
            $table->dropColumn('email_content');
            $table->unsignedBigInteger('temp_campaign_email_content_id');
            $table->foreign('temp_campaign_email_content_id')->references('id')->on('temp_campaign_email_content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_campaign_emails', function (Blueprint $table) {
            $table->text('email_subject')->nullable();
            $table->text('email_content')->nullable();
            $table->dropForeign('temp_campaign_email_content_id');
            $table->dropColumn('temp_campaign_email_content_id');
        });
    }
}
