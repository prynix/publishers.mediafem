<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResoldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('publishers_optimization', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('adserver_id')->unsigned()->index();
			$table->integer('publisher_id')->unsigned()->index();
			$table->string('publisher_name', 80);
			$table->integer('imps');
			$table->integer('blank');
			$table->integer('psa');
			$table->integer('psa_error');
			$table->integer('default_error');
			$table->integer('default');
			$table->integer('kept');
			$table->integer('resold_imps');
			$table->integer('rtb');
			$table->float('revenue');
			$table->float('resold_rev');
			$table->float('cost');
			$table->float('profit');
			$table->integer('optimized')->default(0);
			$table->timestamps();
            $table->foreign('adserver_id')->references('adv_id')->on('adservers')->onDelete('cascade');
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
		Schema::drop('publishers_optimization');
	}

}
