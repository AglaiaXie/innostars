<?php

namespace App\Observers;

use App\Mail\CompanySubmitted;
use App\Mail\CompanyRegistration;
use App\Mail\InvestorApproved;
use App\Mail\InvestorRegistration;
use App\Mail\InvestorSubmitted;
use App\Mail\PartnerApproved;
use App\Mail\PartnerRegistration;
use App\Mail\PartnerSubmitted;
use App\Models\Company;
use App\Models\InvestorProfile;
use App\Models\PartnerProfile;
use Illuminate\Support\Facades\Mail;

class PartnerObserver
{
    public function created(PartnerProfile $partnerProfile)
    {
        Mail::to($partnerProfile->user->email)->send(new PartnerRegistration());
    }

    public function updating(PartnerProfile $partnerProfile)
    {
        if ($partnerProfile->isDirty(['submit']) && $partnerProfile->submit == true) {
            Mail::to($partnerProfile->user->email)->send(new PartnerSubmitted());
        }

        if ($partnerProfile->isDirty(['approval']) && $partnerProfile->approval == true) {
            Mail::to($partnerProfile->user->email)->send(new PartnerApproved());
        }
    }
}
