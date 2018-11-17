<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Competition;
use App\Models\JoinedCompetition;
use App\Models\JudgeProfile;
use App\Models\JudgingCompetition;
use App\Models\Role;
use App\Models\Score;
use App\Models\User;
use Illuminate\Console\Command;

class AddFixtures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:fixtures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        factory(User::class, 10)
            ->create()
            ->each(function ($user) {
                $user->judge_profile()->save(factory(JudgeProfile::class)->make());
                $user->roles()->save(Role::where('name', 'judge')->first());
                foreach (Competition::all() as $competition) {
                    $user->judge_profile->judging_competitions()->create([
                        'judge_profile_id' => $user->judge_profile->id,
                        'competition_id'   => $competition->id,
                    ]);
                }
            });

        factory(User::class, 20)
            ->create()
            ->each(function ($user) {
                $user->company()->save(factory(Company::class)->make());
                $user->roles()->save(Role::where('name', 'participant')->first());
                foreach (Competition::all() as $competition) {
                    $user->company->joined_competitions()->create([
                        'company_id'     => $user->company->id,
                        'competition_id' => $competition->id,
                    ]);

                    if ($competition->in_session) {
                        /** @var JoinedCompetition $joinedCompetition */
                        $joinedCompetition = array_first(last($user->company->joined_competitions));

                        foreach (JudgeProfile::all() as $judge) {
                            if (rand(0, 10) > 7) {
                                continue;
                            }
                            $score = factory(Score::class)->make();
                            $score->judge_profile_id = $judge->id;
                            $joinedCompetition->scores()->save($score);
                        }
                    }
                }
            });
    }
}
