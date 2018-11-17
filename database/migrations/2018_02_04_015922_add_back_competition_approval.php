<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBackCompetitionApproval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('judging_competitions', function (Blueprint $table) {
            $table->boolean('approval')->default(false);
        });

        Schema::table('joined_competitions', function (Blueprint $table) {
            $table->boolean('approval')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('judging_competitions', function (Blueprint $table) {
            $table->dropColumn('approval');
        });

        Schema::table('joined_competitions', function (Blueprint $table) {
            $table->dropColumn('approval');
        });
    }
}
