<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdserverPublisherTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adserver_publisher', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('adserver_id')->unsigned()->index();
			$table->integer('publisher_id')->unsigned()->index();
			$table->foreign('adserver_id')->references('adv_id')->on('adservers')->onDelete('cascade');
			$table->foreign('publisher_id')->references('pbl_id')->on('publishers')->onDelete('cascade');
			$table->integer('adv_pbl_adserver_key');
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
		Schema::drop('adserver_publisher');
	}

}
