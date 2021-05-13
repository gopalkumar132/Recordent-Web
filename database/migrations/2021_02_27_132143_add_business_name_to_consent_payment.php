<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessNameToConsentPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consent_payment', function (Blueprint $table) {
            $table->string('business_name')->nullable();
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('company_id')->nullable();
            $table->string('authorized_signatory_name')->nullable();
            $table->string('authorized_signatory_dob')->nullable();
            $table->string('directors_email')->nullable();
            $table->string('authorized_signatory_designation')->nullable();
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
            $table->dropColumn('business_name');
            $table->dropColumn('address');
            $table->dropColumn('state');
            $table->dropColumn('city');
            $table->dropColumn('pincode');
            $table->dropColumn('company_id');
            $table->dropColumn('authorized_signatory_name');
            $table->dropColumn('authorized_signatory_dob');
            $table->dropColumn('directors_email');
            $table->dropColumn('authorized_signatory_designation');
        });
    }
}
