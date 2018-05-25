<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionToConstants extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('constants', function(Blueprint $table)
		{
			$table->string('cns_description', 60)->after('cns_value');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('constants', function(Blueprint $table)
		{
			$table->dropColumn('cns_description');
		});
	}

}
