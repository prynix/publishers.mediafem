<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_payments', function(Blueprint $table) {
			$table->increments('admpym_id');
			$table->integer('admpym_billing_id')->unsigned()->index();
			$table->decimal('admpym_amount', 10, 2);
			$table->string('admpym_description', 50);
			$table->timestamps();
                        
                        
                        $table->foreign('admpym_billing_id')->references('admbll_id')->on('admin_billings')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admin_payments');
	}

}
