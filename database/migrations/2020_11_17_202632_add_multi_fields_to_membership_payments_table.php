<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiFieldsToMembershipPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_payments', function (Blueprint $table) {
            $table->string('particular')->nullable();
            $table->integer('consent_id')->nullable();
            $table->integer('due_id')->nullable();
            $table->float('discount')->default(0);
            $table->integer('postpaid')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_payments', function (Blueprint $table) {
            $table->drop('particular');
            $table->drop('consent_id');
            $table->drop('due_id');
            $table->drop('discount');
            $table->drop('postpaid');
        });
    }
}
