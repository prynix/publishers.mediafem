<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSitesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sites', function(Blueprint $table) {
			$table->increments('sit_id');
			$table->integer('sit_publisher_id')->unsigned()->index();
			$table->foreign('sit_publisher_id')->references('pbl_id')->on('publishers')->onDelete('cascade');
			$table->string('sit_name', 70);
			$table->enum('sit_is_validated', array('0','1'))->nullable()->default('0');
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
		Schema::drop('sites');
	}

}
