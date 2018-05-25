<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('platforms', function(Blueprint $table) {
            $table->increments('plt_id');
            $table->string('plt_name', 20);
            $table->string('plt_brand', 30);
            $table->string('plt_logo', 30);
            $table->string('plt_color1', 7);
            $table->string('plt_color2', 7);
            $table->timestamps();
        });
        Schema::table('users', function(Blueprint $table) {
            $table->integer('platform_id')->unsigned()->index()->nullable()->after('activated');
            $table->foreign('platform_id')->references('plt_id')->on('platforms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
