<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaypalDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paypal_details', function(Blueprint $table) {
			$table->increments('ppl_id');
			$table->integer('ppl_publisher_id')->unsigned()->index();
			$table->foreign('ppl_publisher_id')->references('pbl_id')->on('publishers')->onDelete('cascade');
			$table->string('ppl_email', 60);
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
		Schema::drop('paypal_details');
	}

}
