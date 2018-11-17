<?php namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Score;
use App\Models\SemifinalForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ParticipantDashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $preliminary_stage = $user->company->joined_competitions()->competitionType(Competition::NAME_PRELIMINARY_STAGE)->first();
        $semifinal_stage = $user->company->joined_competitions()->competitionType(Competition::NAME_SEMI_FINAL)->first();
        $final_stage = $user->company->joined_competitions()->competitionType(Competition::NAME_FINAL)->first();

        if (object_get($preliminary_stage, 'promoted')) {
            /** @var SemifinalForm $semifinal_form */
            $semifinal_form = $user->semifinal_form()->firstOrCreate([]);
            if (!$semifinal_form->semifinal_form_people()->exists()) {
                $semifinal_form->semifinal_form_people()->create([
                    'name' => 'Main Traveller',
                ]);
            }
        } else {
            $semifinal_form = null;
        }

        if (object_get($semifinal_stage, 'promoted')) {
            /** @var SemifinalForm $semifinal_form */
            $final_form = $user->final_form()->firstOrCreate([]);
        } else {
            $final_form = null;
        }

        return view('participant.page.status', [
            'participant' => $user,
            'online_scores' => Score::whereHas('company', function (Builder $builder) use ($user) {
                $builder->where('company_id', $user->company->getKey())
                    ->whereHas('competition', function (Builder $builder) {
                        $builder->where('name', Competition::NAME_ONLINE);
                    });
            })->where('submit', true)->get(),
            'online_stage' => $user->company->joined_competitions()->competitionType(Competition::NAME_ONLINE)->first(),
            'preliminary_scores' => Score::whereHas('company', function (Builder $builder) use ($user) {
                $builder->where('company_id', $user->company->getKey())
                    ->whereHas('competition', function (Builder $builder) {
                        $builder->where('name', Competition::NAME_PRELIMINARY_STAGE);
                    });
            })->where('submit', true)->get(),
            'preliminary_stage' => $preliminary_stage,
            'semifinal_stage' => $semifinal_stage,
            'semifinal_form' => $semifinal_form,
            'final_stage' => $final_stage,
            'final_form' => $final_form,
        ]);
    }
}
