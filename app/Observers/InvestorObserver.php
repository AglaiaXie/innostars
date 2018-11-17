<?php

namespace App\Observers;

use App\Mail\CompanySubmitted;
use App\Mail\CompanyRegistration;
use App\Mail\InvestorApproved;
use App\Mail\InvestorRegistration;
use App\Mail\InvestorSubmitted;
use App\Models\Company;
use App\Models\InvestorProfile;
use Illuminate\Support\Facades\Mail;

class InvestorObserver
{
    public function created(InvestorProfile $investorProfile)
    {
        Mail::to($investorProfile->user->email)->send(new InvestorRegistration());
    }

    public function updating(InvestorProfile $investorProfile)
    {
        if ($investorProfile->isDirty(['submit']) && $investorProfile->submit == true) {
            Mail::to($investorProfile->user->email)->send(new InvestorSubmitted());
        }

        if ($investorProfile->isDirty(['approval']) && $investorProfile->approval == true) {
            Mail::to($investorProfile->user->email)->send(new InvestorApproved());
        }
    }
}
