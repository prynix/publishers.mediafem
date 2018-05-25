<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsPublisherOptimizationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('publishers_optimization', function(Blueprint $table)
		{
			$table->string('hasToAdjust', 10)->nullable()->after('optimized');//hasToAdjust();
                        $table->string('hasToBeDisabled', 10)->nullable()->after('hasToAdjust');//hasToBeDisabled();
                        $table->string('adServing', 10)->nullable()->after('hasToBeDisabled');//getAdServing();
                        $table->string('bidReduction', 10)->nullable()->after('adServing');//getBidReduction();
                        $table->string('adjustmentCpm', 10)->nullable()->after('bidReduction');//getAdjustmentCpm();
                        $table->string('adjustmentUsd', 10)->nullable()->after('adjustmentCpm');//getAdjustmentUsd();
                        $table->string('profitAdjusted', 10)->nullable()->after('adjustmentUsd');//getProfitAdjusted();
                        $table->string('profitAdserving', 10)->nullable()->after('profitAdjusted');//getProfitAdserving();
                        $table->string('publisherShare', 10)->nullable()->after('profitAdserving');//getPublisherShare();
                        $table->string('publisherDueShare', 10)->nullable()->after('publisherShare');//getPublisherDueShare();
                        $table->string('adtomatikProfitPercent', 10)->nullable()->after('publisherDueShare');//getAdtomatikProfitPercent();
                        $table->string('adtomatikDueProfitPercent', 10)->nullable()->after('adtomatikProfitPercent');//getAdtomatikDueProfitPercent();
                        $table->string('adtomatikDueProfit', 10)->nullable()->after('adtomatikDueProfitPercent');//getAdtomatikDueProfit($table->string('adtomatikDueProfitPercent);
                        $table->string('newAjustedProfitWithParam', 10)->nullable()->after('adtomatikDueProfit');//getNewAjustedProfit($table->string('publisherDueShare);
                        $table->string('adtomatikProfitPercentWithParam', 10)->nullable()->after('newAjustedProfitWithParam');//getAdtomatikProfitPercent($table->string('publisherDueShare);
                        $table->string('action', 20)->nullable()->after('adtomatikProfitPercentWithParam');//getAction();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('publishers_optimization', function(Blueprint $table)
		{
			//
		});
	}

}
