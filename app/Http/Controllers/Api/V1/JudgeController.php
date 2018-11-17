<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use PragmaRX\Countries\Facade;
use SplTempFileObject;

class JudgeController extends Controller
{
    protected $relations = [
        'judge_profile.judging_industries',
    ];

    protected $relations_show = [
        'judge_profile.judging_competitions.competition',
        'judge_profile.judging_industries',
        'judge_profile.judging_sub_industries',
        'judge_profile.interested_industries',
        'judge_profile.interested_sub_industries',
        'judge_profile.resume',
        'judge_profile.photo',
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
            $query->where('name', 'judge');
        });

        $this->permissionCheck($query);

        if ($competition = $request->get('competition')) {
            $query->whereHas('judge_profile.judging_competitions', function (Builder $builder) use ($competition) {
                $builder->where('competition_id', $competition);
            });
        }

        if ($request->get('approved') === 'true') {
            $query->whereHas('judge_profile', function (Builder $builder) {
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
        $user->load('judge_profile');
        $user->judge_profile->update($request->all());
        $user->judge_profile->judging_competitions()->update(['approval' => $user->judge_profile->approval]);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('judge')) {
            $user->delete();

            return response('', Response::HTTP_NO_CONTENT);
        }

        return response('User is not an judge account.', Response::HTTP_UNPROCESSABLE_ENTITY);
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
            'Company Name',
            'Job Title',
            'Phone',
            'Highest Degree Attained',
            'What industry(s) would you like to participate in?',
            'Which stage(s) of the competition are you interested in judging in?',
            'How did you hear about us',
            'Resume',
        ]);

        /** @var User $judge */
        foreach ($this->filterByRequest($request)->get() as $judge) {
            $csv->insertOne([
                $judge->first_name,
                $judge->last_name,
                $judge->email,
                $judge->judge_profile->company_name,
                $judge->judge_profile->title,
                $judge->judge_profile->phone,
                $judge->judge_profile->education,
                $judge->judge_profile->judging_industries->pluck('name')->implode(', '),
                $judge->judge_profile->judging_competitions->reduce(function($carry, $item){return $carry ? $carry . ', ' . $item->competition->name . ':' . $item->competition->city : $item->competition->name . ':' . $item->competition->city;}),
                $judge->judge_profile->refer,
                $judge->judge_profile->resume ? '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $judge->judge_profile->resume->disk_name . '", "' . $judge->judge_profile->resume->filename . '")' : '',

            ]);
        }

        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="judges.csv"');
        header("Cache-control: private");

        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        echo $csv->getContent();
    }

    protected function filterByRequest(Request $request)
    {
        $query = User::with($this->relations)->whereHas('roles', function ($query) {
            $query->where('name', 'judge');
        });

        $this->permissionCheck($query);

        if ($request->get('sxsw') === 'true') {
            $query->where('sxsw', '=', true);
        }

        if ($industry = $request->get('industry')) {
            $query = $query->whereHas('judge_profile.judging_industries', function (Builder $builder) use ($industry) {
                $builder->where('industry_id', $industry);
            });
        }

        if ($competition = $request->get('competition')) {
            $query->whereHas('judge_profile.judging_competitions', function (Builder $builder) use ($competition) {
                $builder->where('competition_id', $competition);
            });
        }

        if (($keyword = $request->get('keyword'))) {
            $query->where(function (Builder $builder) use ($keyword) {
                $builder->whereHas('judge_profile', function (Builder $builder) use ($keyword) {
                    $builder->where('company_name', 'LIKE', "%$keyword%");
                })->orWhereRaw("CONCAT_WS(' ', first_name, last_name) LIKE ?", ["%$keyword%"])
                    ->orWhere('email', 'LIKE', "%$keyword%");;
            });
        }

        if ($request->get('submitted') === 'true') {
            $query->whereHas('judge_profile', function (Builder $builder) {
                $builder->where('submit', '=', 1);
            });
        }

        if ($request->get('unsubmitted') === 'true') {
            $query->whereHas('judge_profile', function (Builder $builder) {
                $builder->where('submit', '=', 0);
            });
        }

        if ($request->get('approved') === 'true') {
            $query->whereHas('judge_profile', function (Builder $builder) {
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
            $query->whereHas('judge_profile.judging_competitions', function (Builder $builder) use ($limitCompetitionIds) {
                $builder->whereIn('competition_id', $limitCompetitionIds);
            });
        }

        if (!$user->can('show-private')) {
            $query->whereHas('judge_profile', function (Builder $builder) {
                $builder->where('approval', '=', 1);
            });
        }
    }
}
