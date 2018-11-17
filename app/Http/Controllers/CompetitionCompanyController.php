<?php namespace App\Http\Controllers;

use App\Mail\JudgeAssigned;
use App\Models\Competition;
use App\Models\JoinedCompetition;
use App\Models\JudgingCompetition;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CompetitionCompanyController extends Controller
{
    public function show(Competition $competition)
    {
        return view('admin.page.competition-show', ['competition' => $competition]);
    }

    public function create()
    {
        return view('admin.page.competition-edit');
    }

    public function edit(Competition $competition, JoinedCompetition $company)
    {
        return view('admin.page.competition.company-edit', ['competition' => $competition, 'company' => $company]);
    }

    public function store(Request $request)
    {
        Competition::create($request->all());

        return redirect();
    }

    public function update(Competition $competition, JoinedCompetition $company, Request $request)
    {
        if ($judges = JudgingCompetition::findMany($request->get('judge_id'))) {
            /** @var JudgingCompetition $judge */
            foreach ($judges as $judge) {
                Score::create([
                    'joined_competition_id'  => $company->getKey(),
                    'judging_competition_id' => $judge->getKey(),
                ]);
            }
        }

        return $this->edit($competition, $company);
    }
}
