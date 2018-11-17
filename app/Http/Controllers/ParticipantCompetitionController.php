<?php namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Countries\Facade;

class ParticipantCompetitionController extends Controller
{
    public function index()
    {
        return view('participant.page.competition', [
            'participant' => Auth::user()->load('company.joined_competitions.competition'),
        ]);
    }

    public function show(Competition $competition)
    {
        return view('participant.page.competition-detail', [
            'participant' => Auth::user(),
            'competition' => $competition,
        ]);
    }

    public function edit(User $user)
    {
        $states = Facade::where('name.common', 'United States')
            ->first()
            ->states
            ->sortBy('name')
            ->pluck('name', 'postal');
        return view('admin.page.participant-edit', ['participant' => $user, 'states' => $states]);
    }

    public function update(User $user, Request $request)
    {
        $user->upate($request->all());

        return redirect();
    }
}