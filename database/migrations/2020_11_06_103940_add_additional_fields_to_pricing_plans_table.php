<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsToPricingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->integer('membership_plan_price')->default(0);
            $table->integer('free_customer_limit')->default(10);
            $table->integer('collection_fee')->default(0);
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
            $table->dropColumn('membership_plan_price');
            $table->dropColumn('free_customer_limit');
            $table->dropColumn('collection_fee');
        });
    }
}
