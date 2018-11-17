<?php namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Countries\Facade;

class JudgeDashboardController extends Controller
{
    public function index()
    {
        return view('judge.page.status', [
            'judge' => Auth::user()->load('judge_profile'),
        ]);
    }

    public function edit(User $user)
    {
        $states = Facade::where('name.common', 'United States')
            ->first()
            ->states
            ->sortBy('name')
            ->pluck('name', 'postal');
        return view ('admin.page.participant-edit', ['participant' => $user, 'states' => $states]);
    }

    public function update(User $user, Request $request)
    {
        $user->upate($request->all());

        return redirect();
    }
}