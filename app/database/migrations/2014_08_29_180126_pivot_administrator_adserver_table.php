<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotAdministratorAdserverTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('administrator_adserver', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('administrator_id')->unsigned()->index();
			$table->integer('adserver_id')->unsigned()->index();
			$table->foreign('administrator_id')->references('adm_id')->on('administrators')->onDelete('cascade');
			$table->foreign('adserver_id')->references('adv_id')->on('adservers')->onDelete('cascade');
                        $table->string('adm_adv_adserver_key', 45);
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('administrator_adserver');
	}

}
