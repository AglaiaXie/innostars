<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompetitionStaticInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('joined_competitions', function (Blueprint $table) {
            $table->integer('industry_rank')->after('competition_id')->default(0);
            $table->float('score_average')->after('competition_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('joined_competitions', function (Blueprint $table) {
            $table->dropColumn('industry_rank');
            $table->dropColumn('score_average');
        });
    }
}
