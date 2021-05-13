<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('reports_individual')->default(0);
            $table->integer('reports_business')->default(0);
            $table->integer('collection_fee_individual')->default(0);
            $table->integer('collection_fee_business')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('reports_individual');
            $table->dropColumn('reports_business');
            $table->dropColumn('collection_fee_individual');
            $table->dropColumn('collection_fee_business');
        });
    }
}
