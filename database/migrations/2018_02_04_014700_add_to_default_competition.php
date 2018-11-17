<?php

use App\Models\Competition;
use App\Models\JoinedCompetition;
use App\Models\JudgingCompetition;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddToDefaultCompetition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $online = Competition::where('name', Competition::NAME_ONLINE)->first();

        $participants = User::whereHas('roles', function ($query) {
            $query->where('name', 'participant');
        })->get();

        /** @var User $participant */
        foreach ($participants as $participant) {
            $participant->company->joined_competitions()->save(new JoinedCompetition([
                'competition_id' => $online->getKey()
            ]));
        }

        $judges = User::whereHas('roles', function ($query) {
            $query->where('name', 'judge');
        })->get();

        /** @var User $judge */
        foreach ($judges as $judge) {
            $judge->judge_profile->judging_competitions()->save(new JudgingCompetition([
                'competition_id' => $online->getKey()
            ]));
        }
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
