<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkConstraintOnDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign('files_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign('companies_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('company_industry', function (Blueprint $table) {
            $table->dropForeign('company_industry_company_id_foreign');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('judge_profiles', function (Blueprint $table) {
            $table->dropForeign('judge_profiles_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('judging_industries', function (Blueprint $table) {
            $table->dropForeign('judging_industries_judge_profile_id_foreign');
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles')->onDelete('cascade');
        });

        Schema::table('interested_industries', function (Blueprint $table) {
            $table->dropForeign('interested_industries_judge_profile_id_foreign');
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles')->onDelete('cascade');
        });

        Schema::table('competition_industries', function (Blueprint $table) {
            $table->dropForeign('competition_industries_competition_id_foreign');
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
        });

        Schema::table('joined_competitions', function (Blueprint $table) {
            $table->dropForeign('joined_competitions_company_id_foreign');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->dropForeign('joined_competitions_competition_id_foreign');
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
        });

        Schema::table('judging_competitions', function (Blueprint $table) {
            $table->dropForeign('judging_competitions_judge_profile_id_foreign');
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles')->onDelete('cascade');
            $table->dropForeign('judging_competitions_competition_id_foreign');
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->dropForeign('scores_joined_competition_id_foreign');
            $table->foreign('joined_competition_id')->references('id')->on('joined_competitions')->onDelete('cascade');
            $table->dropForeign('scores_judge_profile_id_foreign');
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
