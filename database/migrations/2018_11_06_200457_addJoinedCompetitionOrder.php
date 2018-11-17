<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJoinedCompetitionOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('joined_competitions', function (Blueprint $table) {
            $table->integer('judge_order')->after('competition_id')->default(0);
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
            $table->dropColumn('judge_order');
        });
    }
}
