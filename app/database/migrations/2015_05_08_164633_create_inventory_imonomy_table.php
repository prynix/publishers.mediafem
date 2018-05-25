<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryImonomyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventory_imonomy', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('publisher_id')->unsigned()->index();
                        $table->foreign('publisher_id')->references('pbl_id')->on('publishers')->onDelete('cascade');
			$table->integer('site_id')->unsigned()->index();
                        $table->foreign('site_id')->references('sit_id')->on('sites')->onDelete('cascade');
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('day');
                        $table->string('publisher_adserver_id', 50);
			$table->string('publisher_name', 200);
			$table->string('site_adserver_id', 50);
			$table->string('site_name', 200);
			$table->string('country_adserver_id', 50);
			$table->string('country_name', 200);
			$table->integer('views');
			$table->integer('imps');
                        $table->decimal('revenue', 10, 6);
                        $table->decimal('ecpm', 10, 6);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('inventory_imonomy', function(Blueprint $table)
		{
			//
		});
	}

}
