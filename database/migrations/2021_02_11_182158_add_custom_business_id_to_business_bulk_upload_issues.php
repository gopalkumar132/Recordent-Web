<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomBusinessIdToBusinessBulkUploadIssues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_bulk_upload_issues', function (Blueprint $table) {
            $table->string('custom_business_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_bulk_upload_issues', function (Blueprint $table) {
           $table->dropColumn('custom_business_id');
        });
    }
}
