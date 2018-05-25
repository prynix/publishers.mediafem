<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotAdserverCountryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adserver_country', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('adserver_id')->unsigned()->index();
			$table->string('country_id', 2)->index();
			$table->foreign('adserver_id')->references('adv_id')->on('adservers')->onDelete('cascade');
			$table->foreign('country_id')->references('cnt_id')->on('countries')->onDelete('cascade');
                        $table->string('adv_cnt_adserver_key', 50);
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('adserver_country');
	}

}
