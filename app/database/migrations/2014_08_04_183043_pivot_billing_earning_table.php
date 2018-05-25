<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotBillingEarningTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('billing_earning', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('billing_id')->unsigned()->index();
			$table->integer('earning_id')->unsigned()->index();
			$table->foreign('billing_id')->references('bll_id')->on('billings')->onDelete('cascade');
			$table->foreign('earning_id')->references('ern_id')->on('earnings')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('billing_earning');
	}

}
