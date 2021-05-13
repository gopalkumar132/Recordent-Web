<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdToConsentRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consent_request', function (Blueprint $table) {
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
        Schema::table('consent_request', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('authorized_signatory_name');
            $table->dropColumn('authorized_signatory_dob');
            $table->dropColumn('directors_email');
            $table->dropColumn('authorized_signatory_designation');
        });
    }
}
