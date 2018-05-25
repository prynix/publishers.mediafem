<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOptimizedPublishersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('optimized_publishers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('publisher_id')->unsigned()->index();
			$table->float('new_share');
			$table->date('optimized_date');
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
		Schema::drop('optimized_publishers');
	}

}
