<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersOfferCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_offer_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('user_id')->nullable();
			$table->string('offer_code')->nullable();
			$table->tinyInteger('offer_code_status')->default(0)->comment('0- Not Verified, 1- Verified');
			$table->tinyInteger('offer_code_used')->default(0)->comment('0- Not Used, 1- Used');
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
        Schema::dropIfExists('users_offer_codes');
    }
}
