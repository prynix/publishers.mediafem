<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToOptimizedPublishersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('optimized_publishers', function(Blueprint $table) {
            $table->integer('site_id')->unsigned()->index()->nullable()->after('publisher_id');
            $table->integer('placement_id')->unsigned()->index()->nullable()->after('site_id');
            $table->string('country_id', 2)->index()->nullable()->after('placement_id');

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
