<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Competition;
use App\Models\Industry;
use App\Models\JoinedCompetition;
use App\Models\Score;
use App\Models\SubIndustry;
use App\Models\User;
use function GuzzleHttp\default_ca_bundle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index(Competition $competition, Request $request)
    {
        $query = $this->filterByRequest($competition, $request);

        return $query->paginate();
    }

    public function show(Competition $competition)
    {
        return $competition;
    }

    public function getNextCompetitions(JoinedCompetition $company)
    {
        switch ($company->competition->name) {
            case Competition::NAME_ONLINE:
                return Competition::where('name', '=', Competition::NAME_PRELIMINARY_STAGE)
                    ->orderByRaw('date IS NULL ASC, date ASC')->get();
            case Competition::NAME_PRELIMINARY_STAGE:
                return Competition::where('name', '=', Competition::NAME_SEMI_FINAL)
                    ->orderByRaw('date IS NULL ASC, date ASC')->get();
            case Competition::NAME_SEMI_FINAL:
                return Competition::where('name', '=', Competition::NAME_FINAL)
                    ->orderByRaw('date IS NULL ASC, date ASC')->get();
            default:
                return null;
        }
    }

    public function getSelectedNextCompetition(JoinedCompetition $company)
    {
        switch ($company->competition->name) {
            case Competition::NAME_ONLINE:
                $competition = JoinedCompetition::where('company_id', '=', $company->company_id)
                    ->whereHas('competition', function (Builder $buider) {
                        $buider->where('name', '=', Competition::NAME_PRELIMINARY_STAGE);
                    })->first();
                break;
            case Competition::NAME_PRELIMINARY_STAGE:
                $competition = JoinedCompetition::where('company_id', '=', $company->company_id)
                    ->whereHas('competition', function (Builder $buider) {
                        $buider->where('name', '=', Competition::NAME_SEMI_FINAL);
                    })->first();
                break;
            case Competition::NAME_SEMI_FINAL:
                $competition = JoinedCompetition::where('company_id', '=', $company->company_id)
                    ->whereHas('competition', function (Builder $buider) {
                        $buider->where('name', '=', Competition::NAME_FINAL);
                    })->first();
                break;
            case Competition::NAME_FINAL:
                return null;
            default:
                $competition = null;
        }

        if ($competition) {
            return object_get($competition, 'competition_id');
        } else {
            return 0;
        }
    }

    public function update(JoinedCompetition $company, Request $request)
    {
        $company->update($request->all());

        if (!empty($nextCompetitionId = $request->get('next_competition_id'))) {
            switch ($company->competition->name) {
                case Competition::NAME_ONLINE:
                    $existing = JoinedCompetition::where('company_id', '=', $company->company_id)
                        ->whereHas('competition', function (Builder $builder) {
                            $builder->where('name', '=', Competition::NAME_PRELIMINARY_STAGE);
                        })->first();
                    break;
                case Competition::NAME_PRELIMINARY_STAGE:
                    $existing = JoinedCompetition::where('company_id', '=', $company->company_id)
                        ->whereHas('competition', function (Builder $builder) {
                            $builder->where('name', '=', Competition::NAME_SEMI_FINAL);
                        })->first();
                    break;
                case Competition::NAME_SEMI_FINAL:
                    $existing = JoinedCompetition::where('company_id', '=', $company->company_id)
                        ->whereHas('competition', function (Builder $builder) {
                            $builder->where('name', '=', Competition::NAME_FINAL);
                        })->first();
                    break;
                default:
                    $existing = null;
            }


            if ($existing && $nextCompetitionId !== $existing->competition_id) {
                $existing->delete();

                $existing = null;
            }

            if (!$existing) {
                JoinedCompetition::create([
                    'company_id' => $company->company_id,
                    'competition_id' => $nextCompetitionId,
                    'approval' => 1,
                ]);
            }
        }

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function autoAssign(JoinedCompetition $company, Request $request)
    {
        $number = $request->get('number', 0);

        $judges = $company->competition->judges()->withCount('scores')
            ->whereHas('judge.judging_sub_industries', function (Builder $builder) use ($company) {
                $builder->where('sub_industry_id', $company->company->sub_industry->getKey());
            })->whereDoesntHave('scores', function (Builder $builder) use ($company) {
                $builder->where('joined_competition_id', '=', $company->getKey());
            })->orderBy('scores_count')->limit($number)->get();

        foreach ($judges as $judge) {
            Score::create([
                'joined_competition_id' => $company->getKey(),
                'judging_competition_id' => $judge->getKey(),
            ]);

            $number--;

            if ($number === 0) {
                break;
            }
        }

        if ($number === 0) {
            return response('', Response::HTTP_CREATED);
        }

        /** @var JoinedCompetition $company */
        $judges = $company->competition->judges()->withCount('scores')
            ->whereHas('judge.judging_industries', function (Builder $builder) use ($company) {
                $builder->where('industry_id', $company->company->industry->getKey());
            })->whereDoesntHave('scores', function (Builder $builder) use ($company) {
                $builder->where('joined_competition_id', '=', $company->getKey());
            })->orderBy('scores_count')->limit($number)->get();

        foreach ($judges as $judge) {
            Score::create([
                'joined_competition_id' => $company->getKey(),
                'judging_competition_id' => $judge->getKey(),
            ]);

            $number--;

            if ($number === 0) {
                break;
            }
        }

        if ($number === 0) {
            return response('', Response::HTTP_CREATED);
        }

        /** @var JoinedCompetition $company */
        $judges = $company->competition->judges()->withCount('scores')
            ->whereDoesntHave('scores', function (Builder $builder) use ($company) {
                $builder->where('joined_competition_id', '=', $company->getKey());
            })
            ->orderBy('scores_count')
            ->limit($number)->get();

        foreach ($judges as $judge) {
            Score::create([
                'joined_competition_id' => $company->getKey(),
                'judging_competition_id' => $judge->getKey(),
            ]);

            $number--;

            if ($number === 0) {
                break;
            }
        }

        return response('', Response::HTTP_CREATED);
    }
}
