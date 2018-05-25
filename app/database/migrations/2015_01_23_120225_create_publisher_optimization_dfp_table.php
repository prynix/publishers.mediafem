<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublisherOptimizationDfpTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('publishers_optimization_dfp', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('adserver_id')->unsigned()->index();
            $table->integer('publisher_id')->unsigned()->index();
            $table->string('publisher_name', 100);
            $table->integer('site_id')->unsigned()->index();
            $table->string('site_name', 80);
            $table->integer('placement_id')->unsigned()->index();
            $table->string('placement_name', 80);
            $table->string('country_id', 2)->index();
            $table->integer('adserver_imps');
            $table->integer('exchange_imps');
            $table->integer('unfilled_imps');
            $table->float('revenue_adserver');
            $table->float('revenue_exchange');
            $table->integer('optimized_adserver')->default(0);
            $table->integer('optimized_exchange')->default(0);
            $table->timestamps();
            
            $table->foreign('site_id')->references('sit_id')->on('sites')->onDelete('cascade');
            $table->foreign('placement_id')->references('plc_id')->on('placements')->onDelete('cascade');
            $table->foreign('country_id')->references('cnt_id')->on('countries')->onDelete('cascade');
            $table->foreign('adserver_id')->references('adv_id')->on('adservers')->onDelete('cascade');
            $table->foreign('publisher_id')->references('pbl_id')->on('publishers')->onDelete('cascade');
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
