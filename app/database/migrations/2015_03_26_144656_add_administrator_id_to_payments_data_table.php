<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdministratorIdToPaymentsDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bank_details', function(Blueprint $table)
		{
			$table->integer('bnk_administrator_id')->unsigned()->nullable()->index()->after('bnk_publisher_id');
                        $table->foreign('bnk_administrator_id')->references('adm_id')->on('administrators')->onDelete('cascade');
		});
		Schema::table('paypal_details', function(Blueprint $table)
		{
			$table->integer('ppl_administrator_id')->unsigned()->nullable()->after('ppl_publisher_id');
                        $table->foreign('ppl_administrator_id')->references('adm_id')->on('administrators')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bank_details', function(Blueprint $table)
		{
			//
		});
	}

}
