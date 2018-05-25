<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAdserverkeyTypeToAdserverCategory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE adserver_category MODIFY COLUMN adv_ctg_adserver_key VARCHAR(50)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE adserver_category MODIFY COLUMN adv_ctg_adserver_key INT');
	}

}
