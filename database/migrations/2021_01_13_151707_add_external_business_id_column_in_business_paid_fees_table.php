<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExternalBusinessIdColumnInBusinessPaidFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_paid_fees', function (Blueprint $table) {
            $table->string('external_business_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_paid_fees', function (Blueprint $table) {
            $table->dropColumn('external_business_id');
        });
    }
}
