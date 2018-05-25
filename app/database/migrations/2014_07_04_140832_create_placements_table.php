<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlacementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('placements', function(Blueprint $table) {
			$table->increments('plc_id');
			$table->integer('plc_site_id')->unsigned()->index();
			$table->foreign('plc_site_id')->references('sit_id')->on('sites')->onDelete('cascade');
			$table->integer('plc_size_id')->unsigned()->index();
			$table->foreign('plc_size_id')->references('siz_id')->on('sizes')->onDelete('cascade');
			$table->integer('plc_adserver_key');
			$table->string('plc_name', 45);
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
		Schema::drop('placements');
	}

}
