<?php namespace App\Http\Controllers;

use App\Models\Industry;
use App\Models\JudgeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantJudgeController extends Controller
{
    public function index(Request $request)
    {
        $judgeQuery = JudgeProfile::with('user')->where('approval', 1);

        $industryFilter = $request->get('industry');

        if (!empty($industryFilter)) {
            $judgeQuery = $judgeQuery->whereHas('judging_industries', function ($query) use ($request) {
                $query->where('industries.id', $request->get('industry'));
            });
        }

        return view('participant.page.judge', [
            'participant' => Auth::user()->load('company'),
            'judges'      => $judgeQuery->get(),
            'industries'  => Industry::all(),
            'industry_f'  => $industryFilter,
        ]);
    }

    public function show(JudgeProfile $judgeProfile)
    {
        return view('participant.page.judge-detail', [
            'participant' => Auth::user(),
            'judge'       => $judgeProfile->load('user')
        ]);
    }
}
