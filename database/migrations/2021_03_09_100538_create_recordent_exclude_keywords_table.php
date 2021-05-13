<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordentExcludeKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recordent_exclude_keywords', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('exclude_profanity')->nullable();
            $table->string('exclude_single_words')->nullable();
            $table->integer('status')->nullable();
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
        $table->dropColumn('exclude_profanity');
        $table->dropColumn('exclude_single_words');
		$table->dropColumn('status');
    }
}
