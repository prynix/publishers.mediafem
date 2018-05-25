<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTmpAdserverTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_tmp_adserver', function(Blueprint $table) {
			$table->increments('uta_id');
			$table->integer('uta_user_id')->unsigned()->index();
			$table->integer('uta_adserver_id')->unsigned()->index();
			$table->foreign('uta_user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('uta_adserver_id')->references('adv_id')->on('adservers')->onDelete('cascade');
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
		Schema::drop('users_tmp_adserver');
	}

}
