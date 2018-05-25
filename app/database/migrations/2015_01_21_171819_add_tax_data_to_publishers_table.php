<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxDataToPublishersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('publishers', function(Blueprint $table) {
            $table->enum('pbl_tax_complete', array('0','1'))->nullable()->default('0')->after('pbl_alert');
            $table->string('pbl_tax_file', 150)->index()->nullable()->after('pbl_tax_complete');
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
