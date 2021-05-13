<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyTypeBusinessShort extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_type')->nullable();
			$table->string('business_short')->nullable();
			$table->dateTime('profile_verified_at')->nullable();
			$table->dateTime('gstin_verified_at')->nullable();
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
            $table->dropColumn('company_type');
			$table->dropColumn('business_short');
			$table->dropColumn('profile_verified_at');
			$table->dropColumn('gstin_verified_at');
			
        });
    }
}
