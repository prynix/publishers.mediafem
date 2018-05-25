<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToOptimizationTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('publishers_optimization', function(Blueprint $table) {
            $table->integer('site_id')->unsigned()->index()->after('publisher_name');
            $table->string('site_name', 80)->after('site_id');
            $table->integer('placement_id')->unsigned()->index()->after('site_name');
            $table->string('placement_name', 80)->after('placement_id');
            $table->string('country_id', 2)->index()->after('placement_name');
            
            $table->foreign('site_id')->references('sit_id')->on('sites')->onDelete('cascade');
            $table->foreign('placement_id')->references('plc_id')->on('placements')->onDelete('cascade');
            $table->foreign('country_id')->references('cnt_id')->on('countries')->onDelete('cascade');
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
