<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryDfpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventory_dfp', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('publisher_id')->unsigned()->index();
                        $table->foreign('publisher_id')->references('pbl_id')->on('publishers')->onDelete('cascade');
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('day');
			$table->string('publisher_adserver_id', 50);
			$table->string('publisher_name', 200);
			$table->string('site_adserver_id', 50);
			$table->string('site_name', 200);
			$table->string('placement_adserver_id', 50);
			$table->string('placement_name', 200);
                        $table->string('size_adserver_id', 50);
			$table->string('size_name', 200);
			$table->string('country_adserver_id', 50);
			$table->string('country_name', 200);
			$table->integer('imps');
			$table->integer('clicks');
                        $table->decimal('revenue', 10, 6);
              });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('inventory_dfp');
	}

}
