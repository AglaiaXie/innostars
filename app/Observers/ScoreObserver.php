<?php

namespace App\Observers;

use App\Mail\JudgeApproved;
use App\Mail\JudgeAssigned;
use App\Mail\JudgeRegistration;
use App\Models\Competition;
use App\Models\JudgeProfile;
use App\Models\Score;
use Illuminate\Support\Facades\Mail;

class ScoreObserver
{
    public function created(Score $score)
    {
        if ($score->company->competition->name === Competition::NAME_ONLINE) {
            Mail::to($score->judge->judge->user->email)->send(new JudgeAssigned($score->company->company->name));
        }
    }

    public function updated(Score $score)
    {
        if ($score->submit == true) {
            $score->company->refreshScore();
            $score->company->competition->refreshRank($score->company->company->industry_id);
        }
    }

    public function deleted(Score $score)
    {
        $score->company->refreshScore();
    }
}
