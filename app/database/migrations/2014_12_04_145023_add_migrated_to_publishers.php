<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMigratedToPublishers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('publishers', function(Blueprint $table)
		{
			$table->enum('pbl_alert', array('0','1'))->nullable()->default('0')->after('pbl_days_to_billing');
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
			$table->dropColumn('pbl_alert');
		});
	}

}
