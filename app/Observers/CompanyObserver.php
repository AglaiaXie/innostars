<?php

namespace App\Observers;

use App\Mail\CompanySubmitted;
use App\Mail\CompanyRegistration;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;

class CompanyObserver
{
    public function created(Company $company)
    {
        Mail::to($company->user->email)->send(new CompanyRegistration());
    }

    public function updating(Company $company)
    {
        if ($company->isDirty(['submit']) && $company->submit == true) {
            Mail::to($company->user->email)->send(new CompanySubmitted());
        }
    }
}
