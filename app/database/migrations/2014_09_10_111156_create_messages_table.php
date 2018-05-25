<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages', function(Blueprint $table) {
			$table->increments('msg_id');
                        $table->integer('msg_user_id')->unsigned()->index();
                        $table->foreign('msg_user_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('msg_subject', 255);
			$table->string('msg_from', 50);
			$table->string('msg_content', 50000);
			$table->enum('msg_view', array('0','1'))->nullable()->default('0');
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
