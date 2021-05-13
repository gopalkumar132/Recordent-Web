<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvidualBusinessBulkDuePaymentIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_due_payment_upload_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unique_url_code',191);
            $table->string('invoice_no', 30)->nullable();
            $table->string('payment_date',20)->nullable();
            $table->string('payment_amount',20)->nullable();
            $table->text('payment_note')->nullable();
            $table->text('issue')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0- Unseen, 1- Seen');
            $table->bigInteger('added_by');
            $table->enum('issue_type', ['INDIVIDUAL', 'BUSINESS']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bulk_due_payment_upload_issues');
    }
}
