<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionToEarnings extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('earnings', function(Blueprint $table) {
            $table->string('ern_description')->nullable()->after('ern_period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('earnings', function(Blueprint $table) {
            $table->dropColumn('ern_description');
        });
    }

}
