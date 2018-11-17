<?php namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\JoinedCompetition;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JudgeCompetitionCompanyController extends Controller
{
    public function show(Competition $competition, JoinedCompetition $company)
    {
        /** @var User $judge */
        $judge = Auth::user();
        return view('judge.page.competition-company-detail', [
            'judge' => $judge,
            'competition' => $competition,
            'company' => $company,
            'score' => $company->scores()
                ->whereHas('judge', function (Builder $builder) use ($judge, $competition) {
                    $builder->where('judge_profile_id', $judge->judge_profile->getKey())
                        ->where('competition_id', $competition->getKey());
                })->first(),
        ]);
    }

    public function update(Competition $competition, JoinedCompetition $company, Request $request)
    {
        /** @var User $judge */
        $judge = Auth::user();

        $score = $company->scores()
            ->whereHas('judge', function (Builder $builder) use ($judge, $competition) {
                $builder->where('judge_profile_id', $judge->judge_profile->getKey())
                    ->where('competition_id', $competition->getKey());;
            })->first();

        $score->update($request->except(['is_submit']) + ['submit' => $request->get('is_submit')]);

        return redirect( url('judge/competitions'));
    }
}