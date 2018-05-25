<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFaviconShortToPlatformsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('platforms', function(Blueprint $table)
		{
			$table->string('plt_favicon', 70)->nullable()->after('plt_logo');
			$table->string('plt_short', 5)->nullable()->after('plt_brand');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('platforms', function(Blueprint $table)
		{
			//
		});
	}

}
