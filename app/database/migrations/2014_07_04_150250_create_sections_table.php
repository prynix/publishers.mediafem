<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sections', function(Blueprint $table) {
			$table->increments('sct_id');
			$table->integer('sct_site_id')->unsigned()->index();
			$table->foreign('sct_site_id')->references('sit_id')->on('sites')->onDelete('cascade');
			$table->integer('sct_adserver_key');
			$table->string('sct_name', 45);
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
		Schema::drop('sections');
	}

}
