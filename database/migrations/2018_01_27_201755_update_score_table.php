<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropForeign('scores_judge_profile_id_foreign');
            $table->dropColumn('judge_profile_id');
            $table->dropColumn('score');
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->integer('judging_competition_id')->after('joined_competition_id')->unsigned();
            $table->foreign('judging_competition_id')->references('id')->on('judging_competitions');
            $table->integer('create_demand_score')->after('judging_competition_id')->nullable();
            $table->integer('fulfill_demand_score')->after('judging_competition_id')->nullable();
            $table->integer('manage_the_money_score')->after('judging_competition_id')->nullable();
            $table->integer('build_the_team_score')->after('judging_competition_id')->nullable();
            $table->boolean('submit')->default(false);
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
            $table->dropForeign('scores_judging_competition_id_foreign');
            $table->dropColumn('judging_competition_id');
            $table->dropColumn('create_demand_score');
            $table->dropColumn('fulfill_demand_score');
            $table->dropColumn('manage_the_money_score');
            $table->dropColumn('build_the_team_score');
            $table->dropColumn('submit');
            $table->integer('score')->after('comment');
        });
    }
}
