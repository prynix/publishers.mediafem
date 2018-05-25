<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasToOptimizePublishersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('publishers', function(Blueprint $table)
		{
			$table->enum('pbl_has_to_optimize', array('0','1'))->nullable()->default('1')->after('pbl_revenue_share');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('publishers', function(Blueprint $table)
		{
			//
		});
	}

}
