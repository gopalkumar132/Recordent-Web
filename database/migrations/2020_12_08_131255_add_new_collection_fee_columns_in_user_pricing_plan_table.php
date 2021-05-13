<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewCollectionFeeColumnsInUserPricingPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_pricing_plan', function (Blueprint $table) {
            $table->float('collection_fee_tier_1',8,1)->default(0);
            $table->float('collection_fee_tier_2',8,1)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_pricing_plan', function (Blueprint $table) {
            $table->dropColumn('collection_fee_tier_1');
            $table->dropColumn('collection_fee_tier_2');
        });
    }
}
