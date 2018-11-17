<?php

namespace App\Observers;

use App\Mail\JudgeApproved;
use App\Mail\JudgeRegistration;
use App\Mail\JudgeSubmitted;
use App\Models\JudgeProfile;
use Illuminate\Support\Facades\Mail;

class JudgeObserver
{
    public function created(JudgeProfile $judge)
    {
        Mail::to($judge->user->email)->send(new JudgeRegistration());
    }

    public function updating(JudgeProfile $judge)
    {
        if ($judge->isDirty(['submit']) && $judge->submit == true) {
            Mail::to($judge->user->email)->send(new JudgeSubmitted());
        }

        if ($judge->isDirty(['approval']) && $judge->approval == true) {
            Mail::to($judge->user->email)->send(new JudgeApproved());
        }
    }
}
