<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn('create_demand_score');
            $table->dropColumn('fulfill_demand_score');
            $table->dropColumn('manage_the_money_score');
            $table->dropColumn('build_the_team_score');

            $table->integer('pain_point')->after('judging_competition_id')->default(5);
            $table->integer('value_proposition')->after('judging_competition_id')->default(5);
            $table->integer('market_analysis')->after('judging_competition_id')->default(5);
            $table->integer('financial_model')->after('judging_competition_id')->default(5);
            $table->integer('expertise')->after('judging_competition_id')->default(5);
            $table->integer('target_market')->after('judging_competition_id')->default(5);
            $table->integer('solution')->after('judging_competition_id')->default(5);
            $table->integer('team_board_adviser')->after('judging_competition_id')->default(5);
            $table->integer('financial')->after('judging_competition_id')->default(5);
            $table->integer('exit_opportunity')->after('judging_competition_id')->default(5);
            $table->integer('strategic_value')->after('judging_competition_id')->default(5);
            $table->integer('spoke_clearly')->after('judging_competition_id')->default(5);
            $table->integer('attitude')->after('judging_competition_id')->default(5);
            $table->integer('relate_to_audience')->after('judging_competition_id')->default(5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->integer('create_demand_score')->after('judging_competition_id')->nullable();
            $table->integer('fulfill_demand_score')->after('judging_competition_id')->nullable();
            $table->integer('manage_the_money_score')->after('judging_competition_id')->nullable();
            $table->integer('build_the_team_score')->after('judging_competition_id')->nullable();

            $table->dropColumn('pain_point');
            $table->dropColumn('value_proposition');
            $table->dropColumn('market_analysis');
            $table->dropColumn('financial_model');
            $table->dropColumn('expertise');
            $table->dropColumn('target_market');
            $table->dropColumn('solution');
            $table->dropColumn('team_board_adviser');
            $table->dropColumn('financial');
            $table->dropColumn('exit_opportunity');
            $table->dropColumn('strategic_value');
            $table->dropColumn('spoke_clearly');
            $table->dropColumn('attitude');
            $table->dropColumn('relate_to_audience');
        });
    }
}
