<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceNoToIndividualBulkIssue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('individual_bulk_upload_issues', function (Blueprint $table) {
            $table->text('invoice_no')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individual_bulk_upload_issues', function (Blueprint $table) {
            $table->dropColumn('invoice_no');
        });
    }
}
