<?php namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class JudgeCompetitionController extends Controller
{
    public function index()
    {
        /** @var User $judge */
        $judge = Auth::user();

        return view('judge.page.competition', [
            'judge' => $judge,
            'competitions' => $judge->judge_profile->judging_competitions()->where('approval', true)
                ->get()
        ]);
    }
}
