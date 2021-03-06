<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotAdserverUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adserver_user', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('adserver_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('adserver_id')->references('adv_id')->on('adservers')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('adserver_user');
	}

}
