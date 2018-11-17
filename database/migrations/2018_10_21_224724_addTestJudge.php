<?php

use App\Models\Competition;
use App\Models\JudgeProfile;
use App\Models\Role;
use App\Models\SubCompetition;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddTestJudge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        for ($i = 1; $i <= 50; $i++) {
            /** @var User $user */
            $user = User::create([
                'first_name' => 'Test Judge',
                'last_name' => $i,
                'email' => 'judge_' . $i . '@ucia.com',
                'password' => bcrypt('123456'),
            ]);

            $user->roles()->save(Role::where('name', 'judge')->first());

            /** @var JudgeProfile $profile */
            $profile = $user->judge_profile()->create([
                'approval' => true,
                'submit' => true,
            ]);


            $profile->judging_competitions()->create([
                'competition_id' => Competition::where('name', '=', Competition::NAME_FINAL)->first()->getKey(),
                'approval' => 1,
            ]);

            $profile->judging_sub_competitions()->saveMany(SubCompetition::all());
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
