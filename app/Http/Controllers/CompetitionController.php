<?php namespace App\Http\Controllers;

use App\Mail\JudgeAssigned;
use App\Models\Company;
use App\Models\Competition;
use App\Models\Industry;
use App\Models\JoinedCompetition;
use App\Models\JudgeProfile;
use App\Models\JudgingCompetition;
use App\Models\Score;
use App\Models\SubIndustry;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use League\Csv\Writer;
use SplTempFileObject;

class CompetitionController extends Controller
{
    const AUTO_ASSIGN_LIMIT = 15;

    public function index()
    {
        /** @var Builder $query */
        $query = Competition::orderBy('name', 'asc');

        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('competition_admin')) {
            /** @var Competition $competition */
            $competition = Competition::where('admin_user_id', $user->getKey())->first();
            $query = $query->whereIn('id', [$competition->getKey()]);
        }

        return view('admin.page.competition-list', ['competitions' => $query->get()]);
    }

    public function show(Competition $competition, Request $request)
    {
        switch ($competition->name) {
            case Competition::NAME_ONLINE:
                $stageFilter = Competition::isPreliminary()->get();
                $promoteStages = new Collection();
                break;
            case Competition::NAME_PRELIMINARY_STAGE:
                $stageFilter = null;
                $promoteStages = new Collection();
                break;
            case Competition::NAME_SEMI_FINAL:
                $stageFilter = null;
                $promoteStages = new Collection();
                break;
            default:
            case Competition::NAME_FINAL:
                $stageFilter = null;
                $promoteStages = new Collection();
                break;
        }

        return view('admin.page.competition-show', [
            'competition' => $competition,
            'companies' => $this->filterByRequest($competition, $request),
            'industries' => Industry::all(),
            'stageFilter' => $stageFilter,
            'promoteStages' => $promoteStages,
        ]);
    }

    /**
     * @param Competition $competition
     * @param Request $request
     * @throws \League\Csv\CannotInsertRecord
     */
    public function downloadParticipants(Competition $competition, Request $request)
    {
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne([
            'Company',
            'Area',
            'Unscored',
            'Total Judge',
            'Average Score',
            'Promoted',
        ]);

        /** @var JoinedCompetition $participant */
        foreach ($this->filterByRequest($competition, $request)->values() as $participant) {
            $csv->insertOne([
                object_get($participant, 'company.name'),
                object_get($participant, 'company.industry.name'),
                $participant->scored,
                $participant->total_score,
                $participant->score_average,
                $participant->promoted ? 'Yes' : 'No',
            ]);
        }

        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="competition_contestants.csv"');
        header("Cache-control: private");

        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        echo $csv->getContent();
    }

    /**
     * @param Competition $competition
     * @param Request $request
     * @throws \League\Csv\CannotInsertRecord
     */
    public function downloadJudges(Competition $competition, Request $request)
    {
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne([
            'First Name',
            'Last Name',
            'Area',
            'Scoring',
            'Scored',
        ]);

        /** @var JudgingCompetition $judge */
        foreach ($competition->judges->sortByDesc('scored')->values() as $judge) {
            $csv->insertOne([
                object_get($judge, 'judge.user.first_name'),
                object_get($judge, 'judge.user.last_name'),
                $judge->judge->judging_industries->pluck('name')->implode(', '),
                $judge->scoring,
                $judge->scored,
            ]);
        }

        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="competition_judges.csv"');
        header("Cache-control: private");

        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        echo $csv->getContent();
    }

    protected function filterByRequest(Competition $competition, Request $request)
    {
        $query = $competition->companies();
        //->sortByDesc('score_average')

        if ($industry = $request->get('industry')) {
            $query->whereHas('company', function (Builder $builder) use ($industry) {
                $builder->where('industry_id', $industry);
            });
        }

        if ($city = $request->get('city')) {
            $query->whereHas('company.joined_competitions', function (Builder $builder) use ($city) {
                $builder->where('competition_id', $city);
            });
        }

        if ($request->get('sort') === 'scores') {
            return $query->get()->sortByDesc('scored');
        }

        return $query->get()->sortByDesc('score_average');
    }

    public function update(Competition $competition, Request $request)
    {
        if ($companies = JoinedCompetition::findMany($request->get('company_id'))) {
            /** @var JoinedCompetition $company */
            foreach ($companies as $company) {
                $company->promoted = true;
                $company->save();
                switch ($competition->name) {
                    case Competition::NAME_ONLINE:

                        /** @var JoinedCompetition $preliminaryStage */
                        $preliminaryStage = $company->company->joined_competitions()->whereHas('competition', function (Builder $builder) {
                            $builder->where('name', Competition::NAME_PRELIMINARY_STAGE);
                        })->first();

                        $preliminaryStage->approval = true;
                        $preliminaryStage->save();
                        break;
                    case Competition::NAME_PRELIMINARY_STAGE:
                    case Competition::NAME_SEMI_FINAL:
                        $company->promoted = true;
                        $company->save();

                        if ($nextStage = $request->get('nextStage')) {
                            $company->company->joined_competitions()->save(new JoinedCompetition([
                                'competition_id' => $nextStage,
                                'approval' => true,
                            ]));
                        }
                        break;
                }
            }
        }

        return $this->show($competition, $request);
    }

    public function assign(Competition $competition, Request $request)
    {
        $city = $request->get('city');

        $subIndustryQuery = SubIndustry::query()
            ->rightJoin('judging_sub_industries', 'sub_industries.id', '=', 'judging_sub_industries.sub_industry_id')
            ->rightJoin('judge_profiles', 'judging_sub_industries.judge_profile_id', '=', 'judge_profiles.id')
            ->rightJoin('judging_competitions', 'judge_profiles.id', '=', 'judging_competitions.judge_profile_id')
            ->where('judging_competitions.competition_id', '=', $competition->getKey())
            ->whereNotNull('sub_industries.id')
            ->selectRaw('count(judging_sub_industries.id) as judgeCount, sub_industries.id as subIndustryId')
            ->groupBy('sub_industries.id')
            ->orderBy('judgeCount');

        $subIndustries = $subIndustryQuery->get();

        foreach ($subIndustries as $subIndustry) {
            $query = $competition->companies();
            if ($city) {
                $query->whereHas('company.joined_competitions', function (Builder $builder) use ($city) {
                    $builder->where('competition_id', $city);
                });
            }

            $companies = $query->has('scores', '<', self::AUTO_ASSIGN_LIMIT)
                ->whereHas('company', function (Builder $builder) use ($subIndustry) {
                    $builder->where('sub_industry_id', '=', $subIndustry->subIndustryId);
                })
                ->get();

            /** @var JoinedCompetition $company */
            foreach ($companies as $company) {
                $judges = $competition->judges()->withCount('scores')
                    ->whereHas('judge.judging_sub_industries', function (Builder $builder) use ($subIndustry) {
                        $builder->where('sub_industry_id', $subIndustry->subIndustryId);
                    })->whereDoesntHave('scores', function (Builder $builder) use ($company) {
                        $builder->where('joined_competition_id', '=', $company->getKey());
                    })->orderBy('scores_count')->limit(self::AUTO_ASSIGN_LIMIT - $company->scores()->count())->get();

                foreach ($judges as $judge) {
                    Score::create([
                        'joined_competition_id' => $company->getKey(),
                        'judging_competition_id' => $judge->getKey(),
                    ]);
                }
            }
        }

        $industryQuery = Industry::query()
            ->rightJoin('judging_industries', 'industries.id', '=', 'judging_industries.industry_id')
            ->rightJoin('judge_profiles', 'judging_industries.judge_profile_id', '=', 'judge_profiles.id')
            ->rightJoin('judging_competitions', 'judge_profiles.id', '=', 'judging_competitions.judge_profile_id')
            ->where('judging_competitions.competition_id', '=', $competition->getKey())
            ->whereNotNull('industries.id')
            ->selectRaw('count(judging_industries.id) as judgeCount, industries.id as industryId')
            ->groupBy('industries.id')
            ->orderBy('judgeCount');

        $industries = $industryQuery->get();


        foreach ($industries as $industry) {
            $query = $competition->companies();
            if ($city) {
                $query->whereHas('company.joined_competitions', function (Builder $builder) use ($city) {
                    $builder->where('competition_id', $city);
                });
            }

            $companies = $query->has('scores', '<', self::AUTO_ASSIGN_LIMIT)
                ->whereHas('company', function (Builder $builder) use ($industry) {
                    $builder->where('industry_id', '=', $industry->industryId);
                })->get();


            /** @var JoinedCompetition $company */
            foreach ($companies as $company) {
                $judges = $competition->judges()->withCount('scores')
                    ->whereHas('judge.judging_industries', function (Builder $builder) use ($industry) {
                        $builder->where('industry_id', $industry->industryId);
                    })->whereDoesntHave('scores', function (Builder $builder) use ($company) {
                        $builder->where('joined_competition_id', '=', $company->getKey());
                    })->orderBy('scores_count')->limit(self::AUTO_ASSIGN_LIMIT - $company->scores()->count())->get();

                foreach ($judges as $judge) {
                    Score::create([
                        'joined_competition_id' => $company->getKey(),
                        'judging_competition_id' => $judge->getKey(),
                    ]);
                }
            }
        }

        $query = $competition->companies();

        if ($city) {
            $query->whereHas('company.joined_competitions', function (Builder $builder) use ($city) {
                $builder->where('competition_id', $city);
            });
        }

        $companies = $query->has('scores', '<', self::AUTO_ASSIGN_LIMIT)->get();

        /** @var JoinedCompetition $company */
        foreach ($companies as $company) {
            $judges = $competition->judges()->withCount('scores')
                ->whereDoesntHave('scores', function (Builder $builder) use ($company) {
                    $builder->where('joined_competition_id', '=', $company->getKey());
                })
                ->orderBy('scores_count')
                ->limit(self::AUTO_ASSIGN_LIMIT - $company->scores()->count())->get();

            foreach ($judges as $judge) {
                Score::create([
                    'joined_competition_id' => $company->getKey(),
                    'judging_competition_id' => $judge->getKey(),
                ]);
            }
        }

        return redirect('/admin/competitions/' . $competition->getKey() . ($city ? "?city=$city" : ''));
    }
}
