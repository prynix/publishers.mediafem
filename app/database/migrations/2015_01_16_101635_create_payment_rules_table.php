<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentRulesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('payment_rules', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('placement_id')->unsigned()->index();
            $table->foreign('placement_id')->references('plc_id')->on('placements')->onDelete('cascade');
            $table->string('country_id', 2)->index();
            $table->foreign('country_id')->references('cnt_id')->on('countries')->onDelete('cascade');
            $table->integer('payment_rule_id');
            $table->string('name', 200);
            $table->integer('share');
            $table->timestamps();
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
