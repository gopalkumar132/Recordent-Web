<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentModeColumnDoubleToStringInMakePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('make_payment', function (Blueprint $table) {
            $table->string('payment_mode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('make_payment', function (Blueprint $table) {
            $table->float('payment_mode')->nullable();
        });
    }
}
