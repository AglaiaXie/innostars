<?php namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\File;
use App\Models\PartnerCompetition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class PartnerProfileController extends Controller
{
    public function editInformation()
    {
        return view('partner.page.profile.information', [
            'user' => Auth::user()->load('partner_profile'),
        ]);
    }

    public function saveInformation(Request $request)
    {
        /** @var User $user */
        $user = Auth::user()->load('partner_profile');

        $user->partner_profile->update($request->all());

        if ($real_logo = $request->file('logo')) {
            \Log::info('..........save logo');
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($real_logo->path()));

            if ($user->partner_profile->real_logo) {
                Storage::disk('files')->delete($user->partner_profile->real_logo->id);
                $user->partner_profile->real_logo->delete();
            }

            $user->partner_profile->real_logo()->associate(File::create([
                'filename' => $real_logo->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id' => $user->getKey(),
            ]));

            $user->partner_profile->save();
        }

        if ($user->partner_profile->current_step === 1) {
            $user->partner_profile->update(['current_step' => 2]);
        }

        $request->session()->flash('message', 'Partner information saved.');

        return redirect('/partner/profile/preference');
    }

    public function editPreference()
    {
        /** @var User $user */
        $user = Auth::user()->load('partner_profile');
        return view('partner.page.profile.preference', [
            'user'                  => $user,
            'competitions'          => Competition::orderBy('name')->get(),
            'competitions_selected' => $user->partner_profile->participating_competitions->pluck('competition_id')->toArray(),
        ]);
    }

    public function savePreference(Request $request)
    {
        $request->validate([
            'participating_competitions'  => 'required',
        ], [
            'participating_competitions.required'  => 'Please select at least 1 competition',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('partner_profile');

        $user->partner_profile->update($request->all());

        /** @var array $participatingCompetitionIds */
        $participatingCompetitionIds = $request->input('participating_competitions');

        $user->partner_profile->participating_competitions()->whereNotIn('competition_id', $participatingCompetitionIds)->delete();

        $currentParticipatingCompetitionIds = $user->partner_profile->participating_competitions()
            ->get()->pluck('competition_id')->toArray();

        $idsToAdd = array_diff($participatingCompetitionIds, $currentParticipatingCompetitionIds);

        foreach ($idsToAdd as $id) {
            $user->partner_profile->participating_competitions()->save(new PartnerCompetition([
                'competition_id' => $id,
            ]));
        }

        if ($user->partner_profile->current_step === 2) {
            $user->partner_profile->update(['current_step' => 3]);
        }

        $request->session()->flash('message', 'Partner preference saved.');

        return redirect('/partner/profile/file');
    }

    public function editFile()
    {
        return view('partner.page.profile.file', [
            'user' => Auth::user()->load('partner_profile'),
        ]);
    }

    public function saveFile(Request $request)
    {
        $request->validate([
            'logo'  => 'max:1024|mimes:jpg,jpeg',
            'document' => 'max:10240',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('partner_profile');

        if ($logo = $request->file('logo')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($logo->path()));

            if ($user->partner_profile->logo) {
                Storage::disk('files')->delete($user->partner_profile->logo->id);
                $user->partner_profile->logo->delete();
            }

            $user->partner_profile->logo()->associate(File::create([
                'filename'  => $logo->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id'   => $user->getKey(),
            ]));

            $user->partner_profile->save();
        }

        if ($document = $request->file('document')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($document->path()));

            if ($user->partner_profile->resume) {
                Storage::disk('files')->delete($user->partner_profile->document->id);
                $user->partner_profile->document->delete();
            }

            $user->partner_profile->document()->associate(File::create([
                'filename'  => $document->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id'   => $user->getKey(),
            ]));

            $user->partner_profile->save();
        }

        if ($logo || $document) {
            $request->session()->flash('message', 'Document uploaded.');
        } else {
            $request->session()->flash('message', 'No file uploaded.');
        }

        $bag = new MessageBag();

        if (!$user->partner_profile->logo) {
            $bag->add('logo', 'Please upload logo.');
        }

        if (!$user->partner_profile->document) {
            $bag->add('document', 'Please upload supporting document.');
        }

        if ($bag->count()) {
            return redirect()->back()->with('errors', session()->get('errors', new ViewErrorBag)->put('default', $bag));
        }

        if ($user->partner_profile->current_step === 3) {
            $user->partner_profile->update(['current_step' => 4]);
        }

        $request->session()->flash('message', 'Partner file saved.');

        return redirect('/partner/profile/submit');
    }

    public function editSubmit()
    {
        return view('partner.page.profile.submit', [
            'user' => Auth::user()->load('partner_profile'),
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
        $user = Auth::user()->load('partner_profile');

        $user->partner_profile->submit = true;
        $user->partner_profile->save();

        return redirect('/partner/profile/submit');
    }
}
