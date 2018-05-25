<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSizesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sizes', function(Blueprint $table) {
			$table->increments('siz_id');
			$table->integer('siz_height')->nullable();
			$table->integer('siz_width')->nullable();
			$table->string('siz_name', 45);
			$table->enum('siz_is_active', array('0','1'))->nullable()->default('0');
			$table->integer('siz_size_type_id')->unsigned()->index();
			$table->foreign('siz_size_type_id')->references('siz_typ_id')->on('size_types')->onDelete('cascade');
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
		Schema::drop('sizes');
	}

}
