<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompetitionParticipantController extends Controller
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
        $query = $competition->companies()->with(['company.user.company', 'company.industry']);

        if ($industry = $request->get('industry')) {
            $query = $query->whereHas('company.industry', function (Builder $builder) use ($industry) {
                $builder->where('industry_id', $industry);
            });
        }

        if ($competition = $request->get('competition')) {
            $query->whereHas('company.joined_competitions', function (Builder $builder) use ($competition) {
                $builder->where('competition_id', $competition);
            });
        }


        if (($keyword = $request->get('keyword'))) {
            $query->where(function (Builder $builder) use ($keyword) {
                $builder->whereHas('company', function (Builder $builder) use ($keyword) {
                    $builder->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('contact_name', 'LIKE', "%$keyword%")
                        ->orWhere('contact_email', 'LIKE', "%$keyword%");
                })->orWhereHas('company.user', function (Builder $builder) use ($keyword) {
                    $builder->whereRaw("CONCAT_WS(' ', first_name, last_name) LIKE ?", ["%$keyword%"])
                        ->orWhere('email', 'LIKE', "%$keyword%");
                });
            });
        }

        $sortField = $request->get('sortField', 'score_average');
        $sortOrder = $request->get('sortOrder', 'desc');
        $query->orderBy($sortField, $sortOrder);

        return $query;
    }
}
