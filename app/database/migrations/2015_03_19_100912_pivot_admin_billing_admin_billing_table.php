<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotAdminBillingAdminBillingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_billing_earning', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('billing_id')->unsigned()->index();
			$table->integer('earning_id')->unsigned()->index();
			$table->foreign('billing_id')->references('admbll_id')->on('admin_billings')->onDelete('cascade');
			$table->foreign('earning_id')->references('admern_id')->on('admin_earnings')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admin_billing_admin_billing');
	}

}
