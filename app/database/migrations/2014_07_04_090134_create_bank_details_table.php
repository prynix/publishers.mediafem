<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_details', function(Blueprint $table) {
			$table->increments('bnk_id');
			$table->integer('bnk_publisher_id')->unsigned()->index();
			$table->foreign('bnk_publisher_id')->references('pbl_id')->on('publishers')->onDelete('cascade');
			$table->string('bnk_country_id', 2)->index();
			$table->foreign('bnk_country_id')->references('cnt_id')->on('countries')->onDelete('cascade');
			$table->string('bnk_account_name', 45);
			$table->string('bnk_account_number', 45);
			$table->string('bnk_bank_name', 45);
			$table->string('bnk_city', 45);
			$table->string('bnk_bic_code', 45);
			$table->string('bnk_intermediary_bank', 45);
			$table->string('bnk_cbu', 45);
			$table->string('bnk_cuit', 45);
			$table->string('bnk_route_code', 45);
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
		Schema::drop('bank_details');
	}

}
