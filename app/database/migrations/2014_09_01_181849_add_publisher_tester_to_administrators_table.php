<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublisherTesterToAdministratorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('administrators', function(Blueprint $table)
		{
			$table->integer('adm_publisher_tester')->unsigned()->index()->after('adm_user_id')->nullable();
			$table->foreign('adm_publisher_tester')->references('pbl_id')->on('publishers')->onDelete('set null');
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
			$table->dropColumn('adm_publisher_tester');
		});
	}

}
