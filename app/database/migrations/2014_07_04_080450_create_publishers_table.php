<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePublishersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('publishers', function(Blueprint $table) {
			$table->increments('pbl_id');
			$table->integer('pbl_user_id')->unsigned()->index();
			$table->foreign('pbl_user_id')->references('id')->on('users')->onDelete('cascade');
			$table->integer('pbl_media_buyer_id')->unsigned()->index()->nullable();
			$table->foreign('pbl_media_buyer_id')->references('adm_id')->on('administrators')->onDelete('set null');
			$table->string('pbl_name', 60);
			$table->enum('pbl_approved', array('0','1'))->nullable()->default('0');
			$table->enum('pbl_is_adnetwork', array('0','1'))->nullable()->default('0');
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
		Schema::drop('publishers');
	}

}
