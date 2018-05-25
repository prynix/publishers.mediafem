<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupToAdministratorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('administrators', function(Blueprint $table)
		{
			$table->integer('adm_group_id')->nullable()->unsigned()->index()->after('adm_user_id');
			$table->foreign('adm_group_id')->references('grp_id')->on('groups')->onDelete('set null');
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
