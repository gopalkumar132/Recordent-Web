<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeadsToUtmContainersCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('utm_containers_campaigns', function (Blueprint $table) {
            $table->integer('lead_type')->nullable()->comment = '[1=>hit,2=>conversion]';
            $table->string('lead_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('utm_containers_campaigns', function (Blueprint $table) {
            $table->dropColumn('lead_type')->nullable();
            $table->dropColumn('lead_data')->nullable();
        });
    }
}
