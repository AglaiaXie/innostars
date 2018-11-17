<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\InvestorProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use PragmaRX\Countries\Facade;
use SplTempFileObject;

class InvestorController extends Controller
{
    protected $relations = [
        'investor_profile.interested_industries',
        'investor_profile.sub_competitions',
    ];

    protected $relations_show = [
        'investor_profile.participating_competitions.competition',
        'investor_profile.interested_industries',
        'investor_profile.interested_sub_industries',
        'investor_profile.resume',
        'investor_profile.photo'
    ];

    public function index(Request $request)
    {
        return $this->filterByRequest($request)->paginate($request->get('perPage'));
    }

    public function show(User $user)
    {
        return $user->load($this->relations_show);
    }

    public function all(Request $request)
    {
        $query = User::with($this->relations)->whereHas('roles', function ($query) {
            $query->where('name', 'investor');
        });

        $this->permissionCheck($query);

        if ($competition = $request->get('competition')) {
            $query->whereHas('investor_profile.participating_competitions', function (Builder $builder) use ($competition) {
                $builder->where('competition_id', $competition);
            });
        }

        if ($request->get('approved') === 'true') {
            $query->whereHas('investor_profile', function (Builder $builder) {
                $builder->where('approval', '=', 1);
            });
        }

        return $query->get();
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
        $user->load('investor_profile');
        $user->investor_profile->update($request->all());

        if ($request->get('approval', null) === 1) {
            $user->roles()->detach(Role::where('name', 'new_investor')->first());
            $user->roles()->save(Role::where('name', 'investor')->first());
        }

        if ($request->get('approval', null) === 0) {
            $user->roles()->detach(Role::where('name', 'investor')->first());
            $user->roles()->save(Role::where('name', 'new_investor')->first());
        }

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('investor') || $user->hasRole('new_investor')) {
            $user->delete();

            return response('', Response::HTTP_NO_CONTENT);
        }

        return response('User is not an investor account.', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Request $request
     * @throws \League\Csv\CannotInsertRecord
     */
    public function download(Request $request)
    {
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne([
            'First Name',
            'Last Name',
            'Email',
            'How did you hear about us',
            'Company',
            'Job Title',
            'Phone',
        ]);

        /** @var User $investor */
        foreach ($this->filterByRequest($request)->get() as $investor) {

            $record = [
                $investor->first_name,
                $investor->last_name,
                $investor->email,
                $investor->investor_profile->refer,
                $investor->investor_profile->company_name,
                $investor->investor_profile->title,
                $investor->investor_profile->phone,
            ];

            $csv->insertOne($record);
        }

        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="investors.csv"');
        header("Cache-control: private");

        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        echo $csv->getContent();
    }

    protected function filterByRequest(Request $request)
    {
        $query = User::with($this->relations)->whereHas('roles', function ($query) {
            $query->whereIn('name', ['new_investor', 'investor']);
        });

        $this->permissionCheck($query);

        if ($request->get('sxsw') === 'true') {
            $query->where('sxsw', '=', true);
        }

        if (($keyword = $request->get('keyword'))) {
            $query->where(function (Builder $builder) use ($keyword) {
                $builder->whereHas('investor_profile', function (Builder $builder) use ($keyword) {
                    $builder->where('company_name', 'LIKE', "%$keyword%");
                })->orWhereRaw("CONCAT_WS(' ', first_name, last_name) LIKE ?", ["%$keyword%"])
                    ->orWhere('email', 'LIKE', "%$keyword%");;
            });
        }

        if ($industry = $request->get('industry')) {
            $query = $query->whereHas('investor_profile.interested_industries', function (Builder $builder) use ($industry) {
                $builder->where('industry_id', $industry);
            });
        }

        if ($competition = $request->get('competition')) {
            $query->whereHas('investor_profile.participating_competitions', function (Builder $builder) use ($competition) {
                $builder->where('competition_id', $competition);
            });
        }

        if ($request->get('submitted') === 'true') {
            $query->whereHas('investor_profile', function (Builder $builder) {
                $builder->where('submit', '=', 1);
            });
        }

        if ($request->get('approved') === 'true') {
            $query->whereHas('investor_profile', function (Builder $builder) {
                $builder->where('approval', '=', 1);
            });
        }

        $query->orderBy($request->get('sortBy', 'created_at'), $request->get('sortDirection', 'desc'));

        return $query;
    }

    protected function permissionCheck(Builder $query){
        /** @var User $user */
        $user = Auth::user();

        if (!$user->can('all-competitions')) {
            $limitCompetitionIds = $user->competitions();
            $query->whereHas('investor_profile.participating_competitions', function (Builder $builder) use ($limitCompetitionIds) {
                $builder->whereIn('competition_id', $limitCompetitionIds);
            });
        }

        if (!$user->can('show-private')) {
            $query->whereHas('investor_profile', function (Builder $builder) {
                $builder->where('approval', '=', 1);
            });
        }
    }
}
