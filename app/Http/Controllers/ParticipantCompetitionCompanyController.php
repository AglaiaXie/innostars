<?php namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\JoinedCompetition;
use Illuminate\Support\Facades\Auth;

class ParticipantCompetitionCompanyController extends Controller
{
    public function show(Competition $competition, JoinedCompetition $company)
    {
        return view('participant.page.competition-company-detail', [
            'participant' => Auth::user(),
            'competition' => $competition,
            'company'     => $company->load(['company', 'scores.judge'])
        ]);
    }
}