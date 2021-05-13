<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOfferCodeResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_offer_code_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('user_id')->nullable();
			$table->string('offer_code_endpoint')->nullable();
			$table->text('response')->nullable();
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
        Schema::dropIfExists('user_offer_code_responses');
    }
}
