<?php namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\File;
use App\Models\Industry;
use App\Models\InvestorCompetition;
use App\Models\SubCompetition;
use App\Models\SubIndustry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class InvestorProfileController extends Controller
{
    public function editInformation()
    {
        return view('investor.page.profile.information', [
            'user' => Auth::user()->load('investor_profile'),
        ]);
    }

    public function saveInformation(Request $request)
    {
        /** @var User $user */
        $user = Auth::user()->load('investor_profile');

        $user->investor_profile->update($request->all());

        if ($user->investor_profile->current_step === 1) {
            $user->investor_profile->update(['current_step' => 2]);
        }

        $request->session()->flash('message', 'Investor information saved.');

        return redirect('/investor/profile/preference');
    }

    public function editPreference()
    {
        /** @var User $investor */
        $user = Auth::user()->load('investor_profile');
        return view('investor.page.profile.preference', [
            'user'                           => $user,
            'industries'                     => Industry::all(),
            'interested_industries_selected' => $user->investor_profile->interested_industries->pluck('id')->toArray(),
            'competitions'                   => Competition::orderByRaw('date IS NULL ASC, date ASC')->get(),
            'competitions_selected'          => $user->investor_profile->participating_competitions->pluck('competition_id')->toArray(),
        ]);
    }

    public function savePreference(Request $request)
    {
        $request->validate([
            'participating_competitions'  => 'required',
            'interested_industries'       => 'required',
        ], [
            'participating_competitions.required'  => 'Please select at least 1 competition',
            'interested_industries.required'       => 'Please select at least one industry',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('investor_profile');

        $user->investor_profile->update($request->all());

        $user->investor_profile->interested_industries()->detach();

        $user->investor_profile->interested_industries()->saveMany(Industry::findMany($request->input('interested_industries')));

        $user->investor_profile->interested_sub_industries()->detach();

        /** @var Industry $industry */
        foreach ($request->input('interested_industries') as $industry_id) {
            if ($subIndustries = $request->input('interested_sub_industry_of_' . $industry_id)) {
                $user->investor_profile->interested_sub_industries()->saveMany(SubIndustry::findMany($subIndustries));
            }
        }

        /** @var array $participatingCompetitionIds */
        $participatingCompetitionIds = $request->input('participating_competitions');

        $user->investor_profile->participating_competitions()->whereNotIn('competition_id', $participatingCompetitionIds)->delete();

        $currentParticipatingCompetitionIds = $user->investor_profile->participating_competitions()
            ->get()->pluck('competition_id')->toArray();

        $idsToAdd = array_diff($participatingCompetitionIds, $currentParticipatingCompetitionIds);

        foreach ($idsToAdd as $id) {
            $user->investor_profile->participating_competitions()->save(new InvestorCompetition([
                'competition_id' => $id,
            ]));
        }

        $subCompetitionIds = [];
        foreach ($participatingCompetitionIds as $competitionId) {
            if ($subCompetitions = $request->input('sub_competition_of_' . $competitionId)) {
                $subCompetitionIds = array_merge($subCompetitionIds, $subCompetitions);
            }
        }

        if (!empty($subCompetitionIds)) {
            $user->investor_profile->sub_competitions()->sync($subCompetitionIds);
        }

        if ($user->investor_profile->current_step === 2) {
            $user->investor_profile->update(['current_step' => 3]);
        }

        $request->session()->flash('message', 'Investor preference saved.');

        return redirect('/investor/profile/file');
    }

    public function editFile()
    {
        return view('investor.page.profile.file', [
            'user' => Auth::user()->load('investor_profile'),
        ]);
    }

    public function saveFile(Request $request)
    {
        $request->validate([
            'photo'  => 'max:10240',
            'resume' => 'max:10240',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('investor_profile');

        $user->investor_profile->update($request->all());

        if ($photo = $request->file('photo')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($photo->path()));

            if ($user->investor_profile->photo) {
                Storage::disk('files')->delete($user->investor_profile->photo->id);
                $user->investor_profile->photo->delete();
            }

            $user->investor_profile->photo()->associate(File::create([
                'filename'  => $photo->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id'   => $user->getKey(),
            ]));

            $user->investor_profile->save();
        }

        if ($resume = $request->file('resume')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($resume->path()));

            if ($user->investor_profile->resume) {
                Storage::disk('files')->delete($user->investor_profile->resume->id);
                $user->investor_profile->resume->delete();
            }

            $user->investor_profile->resume()->associate(File::create([
                'filename'  => $resume->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id'   => $user->getKey(),
            ]));

            $user->investor_profile->save();
        }

        if ($photo || $resume) {
            $request->session()->flash('message', 'Document uploaded.');
        } else {
            $request->session()->flash('message', 'No file uploaded.');
        }
//
//        $bag = new MessageBag();
//
//        if (!$user->investor_profile->photo) {
//            $bag->add('photo', 'Please upload photo.');
//        }
//
//        if (!$user->investor_profile->resume) {
//            $bag->add('resume', 'Please upload resume.');
//        }
//
//        if ($bag->count()) {
//            return redirect()->back()->with('errors', session()->get('errors', new ViewErrorBag)->put('default', $bag));
//        }

        if ($user->investor_profile->current_step === 3) {
            $user->investor_profile->update(['current_step' => 4]);
        }

        $request->session()->flash('message', 'Investor file saved.');

        return redirect('/investor/profile/submit');
    }

    public function editSubmit()
    {
        return view('investor.page.profile.submit', [
            'user' => Auth::user()->load('investor_profile'),
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
        $user = Auth::user()->load('investor_profile');

        $user->investor_profile->submit = true;
        $user->investor_profile->save();

        return redirect('/investor/profile/submit');
    }
}
