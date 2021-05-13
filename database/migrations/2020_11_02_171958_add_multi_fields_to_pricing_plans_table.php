<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiFieldsToPricingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->integer('consent_recordent_report_price')->default(0);
            $table->integer('consent_recordent_report_gst')->default(0);
            $table->integer('consent_comprehensive_report_price')->default(0);
            $table->integer('consent_comprehensive_report_gst')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->integer('consent_recordent_report_price')->default(0);
            $table->integer('consent_recordent_report_gst')->default(0);
            $table->integer('consent_comprehensive_report_price')->default(0);
            $table->integer('consent_comprehensive_report_gst')->default(0);
        });
    }
}
