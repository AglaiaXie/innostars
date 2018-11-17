<?php

use App\Models\Score;
use Illuminate\Database\Migrations\Migration;

class RefreshScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Score::where('submit', '=', 1)->get()->each(function (Score $score) {
            $score->update(['submit' => false]);
            $score->update(['submit' => true]);
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
