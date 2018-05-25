<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnsToSiteTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('sites', function(Blueprint $table) {
                    $table->longText('sit_domain_list')->after('sit_is_validated');
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('sites', function(Blueprint $table) {
                    $table->dropColumn('sit_domain_list');
                });
    }

}
