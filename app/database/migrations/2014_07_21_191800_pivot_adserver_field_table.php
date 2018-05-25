<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotAdserverFieldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adserver_field', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('adserver_id')->unsigned()->index();
			$table->integer('field_id')->unsigned()->index();
			$table->foreign('adserver_id')->references('adv_id')->on('adservers')->onDelete('cascade');
			$table->foreign('field_id')->references('fld_id')->on('fields')->onDelete('cascade');
                        $table->string('adv_fld_name', 45);
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('adserver_field');
	}

}
