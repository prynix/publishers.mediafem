<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDefaultToAdserverCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('adserver_category', function(Blueprint $table)
		{
			$table->enum('adv_ctg_is_default', array('0','1'))->nullable()->default('0')->after('adv_ctg_is_active');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('adserver_category', function(Blueprint $table)
		{
			$table->dropColumn('adv_ctg_is_default');
		});
	}

}
