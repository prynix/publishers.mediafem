<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstantGroupsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('constant_groups', function(Blueprint $table) {
            $table->increments('cns_grp_id');
            $table->string('cns_grp_name', 100);
            $table->timestamps();
        });
        Schema::table('constants', function(Blueprint $table) {
            $table->integer('cns_constant_group_id')->unsigned()->index()->nullable()->after('cns_id');
            $table->foreign('cns_constant_group_id')->references('cns_grp_id')->on('constant_groups')->onDelete('set null');
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
