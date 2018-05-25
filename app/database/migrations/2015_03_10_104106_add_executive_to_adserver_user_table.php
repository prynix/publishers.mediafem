<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExecutiveToAdserverUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('adserver_user', function(Blueprint $table)
		{
			$table->integer('media_buyer_id')->nullable()->unsigned()->index()->after('user_id');
			$table->foreign('media_buyer_id')->references('adm_id')->on('administrators')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('adserver_user', function(Blueprint $table)
		{
			//
		});
	}

}
