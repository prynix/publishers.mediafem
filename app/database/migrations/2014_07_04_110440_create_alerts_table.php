<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAlertsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('alerts', function(Blueprint $table) {
			$table->increments('alr_id');
			$table->integer('alr_publisher_id')->unsigned()->index();
			$table->foreign('alr_publisher_id')->references('pbl_id')->on('publishers')->onDelete('cascade');
			$table->text('alr_message');
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
		Schema::drop('alerts');
	}

}
