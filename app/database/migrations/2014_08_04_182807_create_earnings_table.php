<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEarningsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('earnings', function(Blueprint $table) {
			$table->increments('ern_id');
			$table->integer('ern_publisher_id')->unsigned();
                        $table->foreign('ern_publisher_id')->references('pbl_id')->on('publishers')->onDelete('cascade');
			$table->decimal('ern_amount', 10, 2);
			$table->date('ern_period');
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
		Schema::drop('earnings');
	}

}
