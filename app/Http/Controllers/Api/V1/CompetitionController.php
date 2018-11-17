<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompetitionController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Competition::with('industries', 'sub_competitions');

        if (!$user->can('all-competitions')) {
            $query->whereIn('id', $user->competitions());
        }

        if ($filterBy = $request->get('filterBy')) {
            $query->whereHas($filterBy);
        }

        return $query->orderByRaw('date IS NULL ASC, date ASC')->get();
    }

    public function show(Competition $competition)
    {
        return $competition->load(['industries', 'sub_competitions']);
    }
}
