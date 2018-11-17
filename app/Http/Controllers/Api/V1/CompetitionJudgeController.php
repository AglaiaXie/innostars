<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompetitionJudgeController extends Controller
{
    public function index(Competition $competition, Request $request)
    {
        return $this->filterByRequest($competition, $request)->paginate($request->get('perPage'));
    }

    public function show(Competition $competition)
    {
        return $competition;
    }

    protected function filterByRequest(Competition $competition, Request $request)
    {
        $query = $competition->judges()->with(['judge.user.judge_profile', 'judge.judging_industries']);

        if ($industry = $request->get('industry')) {
            $query = $query->whereHas('judge.judging_industries', function (Builder $builder) use ($industry) {
                $builder->where('industry_id', $industry);
            });
        }

        if (($keyword = $request->get('keyword'))) {
            $query->where(function (Builder $builder) use ($keyword) {
                $builder->whereHas('judge.user', function (Builder $builder) use ($keyword) {
                    $builder->whereRaw("CONCAT_WS(' ', first_name, last_name) LIKE ?", ["%$keyword%"])
                        ->orWhere('email', 'LIKE', "%$keyword%");
                });
            });
        }

        return $query;
    }
}
