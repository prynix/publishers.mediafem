<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profiles', function(Blueprint $table) {
			$table->increments('prf_id');
                        $table->integer('prf_user_id')->unsigned()->index();
                        $table->foreign('prf_user_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('prf_country_id', 2)->index()->nullable();
			$table->foreign('prf_country_id')->references('cnt_id')->on('countries')->onDelete('cascade');
			$table->integer('prf_language_id')->unsigned()->index();
                        $table->foreign('prf_language_id')->references('lng_id')->on('languages')->onDelete('cascade');
			$table->string('prf_name', 50);
			$table->date('prf_birthday');
			$table->string('prf_city', 50);
			$table->string('prf_address', 50);
			$table->string('prf_zip_code', 30);
			$table->string('prf_phone_number', 30);
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
		Schema::drop('profiles');
	}

}
