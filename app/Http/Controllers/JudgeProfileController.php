<?php namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\File;
use App\Models\Industry;
use App\Models\JudgingCompetition;
use App\Models\SubCompetition;
use App\Models\SubIndustry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class JudgeProfileController extends Controller
{
    public function editInformation()
    {
        return view('judge.page.profile.information', [
            'judge' => Auth::user()->load('judge_profile'),
        ]);
    }

    public function saveInformation(Request $request)
    {
        /** @var User $user */
        $user = Auth::user()->load('judge_profile');

        $user->judge_profile->update($request->all());

        if ($user->judge_profile->current_step === 1) {
            $user->judge_profile->update(['current_step' => 2]);
        }

        $request->session()->flash('message', 'Judge information saved.');

        return redirect('/judge/profile/preference');
    }

    public function editPreference()
    {
        /** @var User $judge */
        $judge = Auth::user()->load('judge_profile');
        return view('judge.page.profile.preference', [
            'judge'                          => $judge,
            'industries'                     => Industry::all(),
            'judging_industries_selected'    => $judge->judge_profile->judging_industries->pluck('id')->toArray(),
            'interested_industries_selected' => $judge->judge_profile->interested_industries->pluck('id')->toArray(),
            'competitions'                   => Competition::orderBy('name')
                ->whereDate('date', '>', Carbon::today()->toDateString())->orWhereNull('date')->orWhereDate('date', '=', '2018-01-01')->get(),
            'competitions_selected'          => $judge->judge_profile->judging_competitions->pluck('competition_id')->toArray(),
        ]);
    }

    public function savePreference(Request $request)
    {
        $request->validate([
            'judging_industries'    => 'required',
            'judging_competitions'  => 'required',
            'interested_industries' => 'required',
        ], [
            'judging_industries.required'    => 'Please select at least one industry',
            'judging_competitions.required'  => 'Please select at least 1 competition',
            'interested_industries.required' => 'Please select at least one industry',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('judge_profile');

        $user->judge_profile->update($request->all());

        $user->judge_profile->judging_industries()->detach();

        $user->judge_profile->judging_industries()->saveMany(Industry::findMany($request->input('judging_industries')));

        $user->judge_profile->judging_sub_industries()->detach();

        /** @var Industry $industry */
        foreach ($request->input('judging_industries') as $industry_id) {
            if ($subIndustries = $request->input('sub_industry_of_' . $industry_id)) {
                $user->judge_profile->judging_sub_industries()->saveMany(SubIndustry::findMany($subIndustries));
            }
        }

        $user->judge_profile->interested_industries()->detach();

        $user->judge_profile->interested_industries()->saveMany(Industry::findMany($request->input('interested_industries')));

        $user->judge_profile->interested_sub_industries()->detach();

        /** @var Industry $industry */
        foreach ($request->input('interested_industries') as $industry_id) {
            if ($subIndustries = $request->input('interested_sub_industry_of_' . $industry_id)) {
                $user->judge_profile->interested_sub_industries()->saveMany(SubIndustry::findMany($subIndustries));
            }
        }

        /** @var array $judgingCompetitionIds */
        $judgingCompetitionIds = $request->input('judging_competitions');


        $user->judge_profile->judging_competitions()->whereNotIn('competition_id', $judgingCompetitionIds)->delete();

        $currentJudgingCompetitionIds = $user->judge_profile->judging_competitions()
            ->get()->pluck('competition_id')->toArray();

        $idsToAdd = array_diff($judgingCompetitionIds, $currentJudgingCompetitionIds);

        foreach ($idsToAdd as $id) {
            $user->judge_profile->judging_competitions()->save(new JudgingCompetition([
                'competition_id' => $id,
            ]));
        }

        $subCompetitionIds = [];
        foreach ($judgingCompetitionIds as $competitionId) {
            if ($subCompetitions = $request->input('sub_competition_of_' . $competitionId)) {
                $subCompetitionIds = array_merge($subCompetitionIds, $subCompetitions);
            }
        }

        if (!empty($subCompetitionIds)) {
            $user->judge_profile->judging_sub_competitions()->sync($subCompetitionIds);
        }

        if ($user->judge_profile->current_step === 2) {
            $user->judge_profile->update(['current_step' => 3]);
        }

        $request->session()->flash('message', 'Judge preference saved.');

        return redirect('/judge/profile/addition');
    }

    public function editAddition()
    {
        return view('judge.page.profile.addition', [
            'judge' => Auth::user()->load('judge_profile'),
        ]);
    }

    public function saveAddition(Request $request)
    {
        /** @var User $user */
        $user = Auth::user()->load('judge_profile');

        $user->judge_profile->update($request->all());

        if ($user->judge_profile->current_step === 3) {
            $user->judge_profile->update(['current_step' => 4]);
        }

        $request->session()->flash('message', 'Additional information saved.');

        return redirect('/judge/profile/file');
    }

    public function editFile()
    {
        return view('judge.page.profile.file', [
            'judge' => Auth::user()->load('judge_profile'),
        ]);
    }

    public function saveFile(Request $request)
    {
        $request->validate([
            'photo'  => 'max:10240',
            'resume' => 'max:10240',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('judge_profile');

        if ($photo = $request->file('photo')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($photo->path()));

            if ($user->judge_profile->photo) {
                Storage::disk('files')->delete($user->judge_profile->photo->id);
                $user->judge_profile->photo->delete();
            }

            $user->judge_profile->photo()->associate(File::create([
                'filename'  => $photo->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id'   => $user->getKey(),
            ]));

            $user->judge_profile->save();
        }

        if ($resume = $request->file('resume')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($resume->path()));

            if ($user->judge_profile->resume) {
                Storage::disk('files')->delete($user->judge_profile->resume->id);
                $user->judge_profile->resume->delete();
            }

            $user->judge_profile->resume()->associate(File::create([
                'filename'  => $resume->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id'   => $user->getKey(),
            ]));

            $user->judge_profile->save();
        }

        if ($photo || $resume) {
            $request->session()->flash('message', 'Document uploaded.');
        } else {
            $request->session()->flash('message', 'No file uploaded.');
        }

        $bag = new MessageBag();

        if (!$user->judge_profile->photo) {
            $bag->add('photo', 'Please upload photo.');
        }

        if (!$user->judge_profile->resume) {
            $bag->add('resume', 'Please upload resume.');
        }

        if ($bag->count()) {
            return redirect()->back()->with('errors', session()->get('errors', new ViewErrorBag)->put('default', $bag));
        }

        if ($user->judge_profile->current_step === 4) {
            $user->judge_profile->update(['current_step' => 5]);
        }

        $request->session()->flash('message', 'Judge file saved.');

        return redirect('/judge/profile/submit');
    }

    public function editSubmit()
    {
        return view('judge.page.profile.submit', [
            'judge' => Auth::user()->load('judge_profile'),
        ]);
    }

    public function saveSubmit(Request $request)
    {
        $request->validate([
            'confirm_1' => 'required',
        ], [
            'confirm_1.required' => 'You must confirm that your registration is complete, and the registration will be locked once submitted',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('judge_profile');

        $user->judge_profile->submit = true;
        $user->judge_profile->save();

        return redirect('/judge/profile/submit');
    }
}
