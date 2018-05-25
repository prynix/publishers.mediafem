<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdserversTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adservers', function(Blueprint $table) {
			$table->increments('adv_id');
			$table->string('adv_name', 45);
			$table->string('adv_user', 45);
                        $table->string('adv_password', 45);
                        $table->string('adv_class_name', 45);
                        $table->enum('adv_is_default', array('0','1'))->nullable()->default('0');
			$table->string('adv_token', 50);
                        $table->integer('adv_minutes_to_expire_token');
                        $table->timestamp('adv_token_set');
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
		Schema::drop('adservers');
	}

}
