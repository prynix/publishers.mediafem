<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLineitemidToAdserverSiteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sites', function(Blueprint $table)
		{
			$table->string('sit_lineitem_id', 60)->nullable()->after('sit_categorized_on_adserver');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sites', function(Blueprint $table)
		{
			$table->dropColumn('sit_lineitem_id');
		});
	}

}
