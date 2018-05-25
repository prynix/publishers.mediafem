<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToSitesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('sites', function(Blueprint $table) {
                    $table->enum('sit_validation_type', array('1','2','3'))->nullable()->after('sit_is_validated');
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('sites', function(Blueprint $table) {
                    $table->dropColumn('sit_is_validated');
                });
    }

}
