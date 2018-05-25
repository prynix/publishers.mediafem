<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function(Blueprint $table) {
			$table->increments('pym_id');
			$table->integer('pym_billing_id')->unsigned();
                        $table->foreign('pym_billing_id')->references('bll_id')->on('billings')->onDelete('cascade');
			$table->decimal('pym_amount', 10, 2);
			$table->string('pym_description', 50);
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
		Schema::drop('payments');
	}

}
