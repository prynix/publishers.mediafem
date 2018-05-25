<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediabuyerCommissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('media_buyer_commissions', function(Blueprint $table)
		{
			$table->increments('mbc_id');
			$table->integer('mbc_administrator_id')->unsigned()->index();
			$table->integer('mbc_adserver_id')->unsigned()->index();
			$table->integer('mbc_imps');
			$table->decimal('mbc_revenue', 10, 2);
			$table->decimal('mbc_cost', 10, 2);
			$table->decimal('mbc_profit', 10, 2);
			$table->decimal('mbc_commission', 10, 2);
			$table->datetime('mbc_period');
                        
			$table->timestamps();
                        $table->foreign('mbc_administrator_id')->references('adm_id')->on('administrators')->onDelete('cascade');
                        $table->foreign('mbc_adserver_id')->references('adv_id')->on('adservers')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('media_buyer_commissions', function(Blueprint $table)
		{
			//
		});
	}

}
