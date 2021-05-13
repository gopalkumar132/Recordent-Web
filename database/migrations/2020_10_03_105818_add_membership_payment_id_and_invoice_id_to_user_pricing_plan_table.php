<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMembershipPaymentIdAndInvoiceIdToUserPricingPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_pricing_plan', function (Blueprint $table) {
            $table->integer('membership_payment_id')->default(0);
            $table->string('invoice_id')->nullable();
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
            $table->dropColumn('membership_payment_id');
            $table->dropColumn('invoice_id');
        });
    }
}
