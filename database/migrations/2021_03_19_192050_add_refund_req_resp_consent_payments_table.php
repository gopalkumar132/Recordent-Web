<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefundReqRespConsentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consent_payment', function (Blueprint $table) {
            $table->tinyInteger('refund_status')->nullable();
			$table->text('raw_refund_request')->nullable();
			$table->text('raw_refund_response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consent_payment', function (Blueprint $table) {			
			$table->dropColumn('refund_status');
			$table->dropColumn('raw_refund_request');
			$table->dropColumn('raw_refund_response');
			
        });
    }
}
