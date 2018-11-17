<?php

namespace App\Console;

use App\Mail\CompanyNeedSubmit;
use App\Models\Company;
use App\Models\Competition;
use App\Models\JoinedCompetition;
use App\Models\Score;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $companies = Company::where('submit', false)->get();
            /** @var Company $company */
            foreach ($companies as $company) {
                /** @var JoinedCompetition $joinedPreliminaryCompetition */
                $joinedPreliminaryCompetition = $company->joined_competitions()
                    ->whereHas('competition', function (Builder $builder) {
                        $builder->where('name', Competition::NAME_PRELIMINARY_STAGE);
                    })->first();
                if ($company->submit == false && $company->approval == false && $deadline = object_get($joinedPreliminaryCompetition, 'competition.deadline')) {
                    switch ($days = (int)ceil($deadline->diffInHours(Carbon::now()) / 24 + 1)) {
                        case 21:
                        case 14:
                        case 7:
                        case 3:
                            Mail::to($company->user->email)->send(new CompanyNeedSubmit($days));
                    }
                }
            }
        })->dailyAt('09:00');

        $schedule->call(function () {
            $scores = Score::where('submit', false)->get();
            /** @var Score $score */
            foreach ($scores as $score) {
                if ($score->is_due) {
                    continue;
                }

                switch ($days = ceil($score->due_at->diffInHours(Carbon::now()) / 24)) {
                    case 3:
                    case 2:
                    case 1:
                        echo $score->judge->judge->user->email . ' ' . $days . ' ' . $score->company->company->name;
                    default:
                }
            }
        })->dailyAt('09:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
