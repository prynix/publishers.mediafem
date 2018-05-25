<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImonomySiteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('imonomy_site', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('site_id')->unsigned()->index();
			$table->string('imonomy_id', 10);
			$table->string('imonomy_tag', 15)->nullable();
                        $table->timestamps();
                        $table->foreign('site_id')->references('sit_id')->on('sites')->onDelete('cascade');
		});
		Schema::create('imonomy_publisher', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('publisher_id')->unsigned()->index();
			$table->string('imonomy_id', 10);
                        $table->timestamps();
                        $table->foreign('publisher_id')->references('pbl_id')->on('publishers')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('imonomy_site', function(Blueprint $table)
		{
			//
		});
	}

}
