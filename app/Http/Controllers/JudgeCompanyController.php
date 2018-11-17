<?php namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Countries\Facade;

class JudgeCompanyController extends Controller
{
    public function index()
    {
        return view('judge.page.company', [
            'judge'     => Auth::user()->load('judge_profile'),
            'companies' => Company::with('user')->where('approval', 1)->get(),
        ]);
    }

    public function show(Company $company)
    {
        return view('judge.page.company-detail', [
            'judge'   => Auth::user(),
            'company' => $company,
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