<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\JoinedCompetition;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyScoreController extends Controller
{
    public function index(JoinedCompetition $company, Request $request)
    {
        return $company->scores()->with('judge.judge.user')
            ->orderBy('scores.submit', 'desc')
            ->paginate($request->get('perPage'));
    }

    public function destroy(JoinedCompetition $company, Score $score)
    {
        $score->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
