<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminEarningsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_earnings', function(Blueprint $table) {
			$table->increments('admern_id');
			$table->integer('admern_administrator_id')->unsigned()->index();
			$table->decimal('admern_amount', 10, 2);
			$table->date('admern_period');
			$table->string('admern_description', 255)->nullable();
			$table->timestamps();
                        
                        $table->foreign('admern_administrator_id')->references('adm_id')->on('administrators')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admin_earnings');
	}

}
