<?php namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Industry;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use League\Csv\Writer;
use SplTempFileObject;

class JudgeController extends Controller
{
    public function index()
    {
        return view('admin.page.judge-list');
    }

    public function edit(User $user)
    {
        return view('admin.page.judge-edit', ['judge' => $user]);
    }

    public function update(User $user, Request $request)
    {
        $user->load('judge_profile');
        $user->judge_profile->update($request->all());

        $user->judge_profile->judging_competitions()->update(['approval' => $user->judge_profile->approval]);

        $request->session()->flash('message', 'Status updated.');

        return redirect('admin/judges/' . $user->id . '/edit');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response('', Response::HTTP_NO_CONTENT);
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
            'Assigned',
            'Completed',
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
                $judge->judge_profile->judging_competitions->sum('scoring'),
                $judge->judge_profile->judging_competitions->sum('scored'),
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
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('competition_admin')) {
            /** @var Competition $competition */
            $competition = Competition::where('admin_user_id', $user->getKey())->first();

            $query = User::whereHas('judge_profile.judging_competitions', function (Builder $builder) use ($competition) {
                $builder->where('competition_id', $competition->getKey());
            });
        } else {
            $query = User::whereHas('roles', function ($query) {
                $query->where('name', 'judge');
            });
        }

        if ($industry = $request->get('sxsw') || $user->hasRole('sxsw_admin')) {
            $query->where('sxsw', '=', true);
        }

        if ($industry = $request->get('industry')) {
            $query = $query->whereHas('judge_profile.judging_industries', function (Builder $builder) use ($industry) {
                $builder->where('industry_id', $industry);
            });
        }

        if (($keyword = $request->get('keyword'))) {
            $query->where(function (Builder $builder) use ($keyword) {
                $builder->whereHas('judge_profile', function (Builder $builder) use ($keyword) {
                    $builder->where('company_name', 'LIKE', "%$keyword%");
                });

                $builder->orWhereRaw("CONCAT_WS(' ', first_name, last_name) LIKE ?", ["%$keyword%"]);
            });
        }

        if ($submitted = $request->get('submitted') xor $unsubmitted = $request->get('unsubmitted')) {
            if ($submitted) {
                $query->whereHas('judge_profile', function (Builder $builder) {
                    $builder->where('submit', '=', 1);
                });
            }
            if ($unsubmitted) {
                $query->whereHas('judge_profile', function (Builder $builder) {
                    $builder->where('submit', '=', 0);
                });
            }
        }

        if ($approved = $request->get('approved')) {
            $query->whereHas('judge_profile', function (Builder $builder) {
                $builder->where('approval', '=', 1);
            });
        }

        switch ($sort = $request->get('sort')) {
            case 'name_asc':
                $query->orderBy('first_name')->orderBy('last_name');
                break;
            case 'name_desc':
                $query->orderByDesc('first_name')->orderByDesc('last_name');
                break;
            case 'date_asc':
                $query->orderBy('created_at');
                break;
            case 'date_desc':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        return $query;
    }
}
