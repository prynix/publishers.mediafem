<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesDefaultTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages_default', function(Blueprint $table) {
			$table->increments('msgd_id');
			$table->integer('msgd_group')->unsigned();
			$table->string('msgd_subject', 255);
			$table->string('msgd_from', 50);
			$table->string('msgd_content', 50000);
			$table->integer('msgd_language_id')->unsigned()->index();
                        $table->foreign('msgd_language_id')->references('lng_id')->on('languages')->onDelete('cascade');
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
		Schema::drop('messages_default');
	}

}
