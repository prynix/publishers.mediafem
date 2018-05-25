<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::drop('users_groups');
                Schema::drop('groups');
		Schema::create('groups', function(Blueprint $table)
		{
			$table->increments('grp_id');
			$table->string('grp_name', 60);
			$table->text('grp_description');
                        $table->enum('grp_is_default', array('0','1'))->nullable()->default('0');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('groups');
	}

}
