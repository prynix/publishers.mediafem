<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('countries', function(Blueprint $table) {
			$table->string('cnt_id', 2);
			$table->integer('cnt_currency_id')->unsigned()->index();
			$table->foreign('cnt_currency_id')->references('crr_id')->on('currencies')->onDelete('cascade');
			$table->timestamps();
			$table->primary('cnt_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('countries');
	}

}
