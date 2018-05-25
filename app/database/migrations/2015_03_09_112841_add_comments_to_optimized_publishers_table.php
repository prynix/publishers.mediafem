<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentsToOptimizedPublishersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('optimized_publishers', function(Blueprint $table)
		{
                    $table->string('comments', 30)->nullable()->after('optimized_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('optimized_publishers', function(Blueprint $table)
		{
			//
		});
	}

}
