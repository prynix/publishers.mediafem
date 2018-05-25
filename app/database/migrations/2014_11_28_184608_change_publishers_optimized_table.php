<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePublishersOptimizedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::table('optimized_publishers', function(Blueprint $table) {
                    $table->decimal('previous_profit', 8, 2)->after('publisher_id');
                    $table->decimal('new_profit', 8, 2)->after('previous_profit');
                    $table->decimal('previous_share', 8, 2)->after('new_profit');
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
