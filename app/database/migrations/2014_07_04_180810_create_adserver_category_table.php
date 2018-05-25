<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdserverCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adserver_category', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('adserver_id')->unsigned()->index();
			$table->integer('category_id')->unsigned()->index();
			$table->foreign('adserver_id')->references('adv_id')->on('adservers')->onDelete('cascade');
			$table->foreign('category_id')->references('ctg_id')->on('categories')->onDelete('cascade');
			$table->integer('adv_ctg_adserver_key');
			$table->enum('adv_ctg_is_active', array('0','1'))->nullable()->default('1');
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
		Schema::drop('adserver_category');
	}

}
