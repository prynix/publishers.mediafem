<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBridTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('brid_sites', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('site_id')->unsigned()->index();
			$table->string('brid_id', 10);
		        $table->timestamps();
                        $table->foreign('site_id')->references('sit_id')->on('sites')->onDelete('cascade');
		});
                
		Schema::create('brid_videos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('placement_id')->unsigned()->index();
			$table->string('brid_id', 10);
                        $table->string('url', 1000);
                        $table->string('embed_code', 1000);
                        $table->timestamps();
                        $table->foreign('placement_id')->references('plc_id')->on('placements')->onDelete('cascade');
		});
                
                //DB::statement('ALTER TABLE placements ADD COLUMN plc_brid_video_id INT(10) NOT NULL AFTER plc_name');
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
