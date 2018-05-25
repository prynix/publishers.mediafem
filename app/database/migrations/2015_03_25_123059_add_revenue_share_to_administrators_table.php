<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRevenueShareToAdministratorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('administrators', function(Blueprint $table)
		{
			$table->integer('adm_revenue_share')->nullable()->default(0)->after('adm_publisher_tester');
			$table->decimal('adm_actual_balance', 10, 2)->nullable()->default(0)->after('adm_revenue_share');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('administrators', function(Blueprint $table)
		{
			//
		});
	}

}
