<?php namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Competition;
use App\Models\File;
use App\Models\Industry;
use App\Models\JoinedCompetition;
use App\Models\SubIndustry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use PragmaRX\Countries\Facade;

class ParticipantProfileController extends Controller
{
    public function editCompany()
    {
        /** @var User $user */
        $user = Auth::user()->load('company');

        $states = Facade::where('name.common', 'United States')
            ->first()
            ->states
            ->sortBy('name')
            ->pluck('name', 'postal');

        return view('participant.page.profile.company', [
            'participant' => $user,
            'states' => $states,
            'types' => Company::COMPANY_TYPES,
        ]);
    }

    public function saveCompany(Request $request)
    {
        $request->validate([
            'size' => 'required|integer',
            'established' => 'required|integer',
            'logo' => 'max:1024|mimes:jpg,jpeg',
            'zip_code' => 'required|regex:/^[0-9]{5}$/',
        ], [
            'size.integer' => 'Please enter a valid number',
            'established.integer' => 'Please enter a valid number',
            'zip_code.regex' => 'Please enter an valid 5 digits US zip code',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('company');

        $user->company->update($request->all());

        if ($logo = $request->file('logo')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($logo->path()));

            if ($user->company->logo) {
                Storage::disk('files')->delete($user->company->logo->id);
                $user->company->logo->delete();
            }

            $user->company->logo()->associate(File::create([
                'filename' => $logo->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id' => $user->getKey(),
            ]));

            $user->company->save();
        }

        if ($user->company->current_step === 1) {
            $user->company->update(['current_step' => 2]);
        }

        $request->session()->flash('message', 'Company information saved.');

        return redirect('/participant/profile/contact');
    }

    public function editContact()
    {
        return view('participant.page.profile.contact', [
            'participant' => Auth::user()->load('company'),
        ]);
    }

    public function saveContact(Request $request)
    {
        $request->validate([
            'contact_phone' => 'required',
            'contact_email' => 'required|email',
            'contact_photo' => 'max:1024|mimes:jpg,jpeg',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('company');

        $user->company->update($request->all());

        if ($contactPhoto = $request->file('contact_photo')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($contactPhoto->path()));

            if ($user->company->contact_photo) {
                Storage::disk('files')->delete($user->company->contact_photo->id);
                $user->company->contact_photo->delete();
            }

            $user->company->contact_photo()->associate(File::create([
                'filename' => $contactPhoto->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id' => $user->getKey(),
            ]));

            $user->company->save();
        }

        if ($user->company->current_step === 2) {
            $user->company->update(['current_step' => 3]);
        }

        $request->session()->flash('message', 'Contact information saved.');

        return redirect('/participant/profile/project');
    }

    public function editProject()
    {
        /** @var User $user */
        $user = Auth::user()->load('company');

        return view('participant.page.profile.project', [
            'participant' => $user,
            'competitions' => Competition::isOnline()->get(),
            'cooperations' => Company::COOPERATION_TYPES,
            'cooperations_selected' => explode(',', object_get($user, 'company.cooperation', '')),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function saveProject(Request $request)
    {
        /** @var User $user */
        $user = Auth::user()->load('company');

        $cooperationInput = $request->input('cooperation');
        $cooperation = $cooperationInput ? implode(',', $request->input('cooperation')) : null;

        $params = $request->all();

        if (!$request->get('cooperation_alt_checked')) {
            $params['cooperation_alt'] = null;
        }

        $competitionId = $request->get('competition_id');

        /** @var JoinedCompetition $existingPreliminary */
        if ($existingPreliminary = $user->company->joined_competitions()->isPreliminary()->first()) {
            if ($competitionId !== $existingPreliminary->getKey()) {
                $existingPreliminary->delete();

                $user->company->joined_competitions()->save(new JoinedCompetition([
                    'competition_id' => $competitionId,
                ]));
            }
        } elseif ($competitionId != Competition::where('name', '=', Competition::NAME_ONLINE)->first()->getKey()) {
            $user->company->joined_competitions()->save(new JoinedCompetition([
                'competition_id' => $competitionId,
            ]));
        }

        $industryId = $request->input('industry_id_for_' . $request->get('competition_id'));

        $subIndustryId = $request->input('sub_industry_of_' . $industryId);

        $user->company->update(array_merge($params, [
            'cooperation' => $cooperation,
            'industry_id' => $industryId,
            'sub_industry_id' => $subIndustryId,
        ]));

        if ($user->company->current_step === 3) {
            $user->company->update(['current_step' => 4]);
        }

        $request->session()->flash('message', 'Project information saved.');

        return redirect('/participant/profile/addition');
    }

    public function editAddition()
    {
        return view('participant.page.profile.addition', [
            'participant' => Auth::user()->load('company'),
        ]);
    }

    public function saveAddition(Request $request)
    {
        /** @var User $user */
        $user = Auth::user()->load('company');

        $user->company->update($request->all());

        if ($user->company->current_step === 4) {
            $user->company->update(['current_step' => 5]);
        }

        $request->session()->flash('message', 'Additional information saved.');

        return redirect('/participant/profile/file');
    }

    public function editFile()
    {
        return view('participant.page.profile.file', [
            'participant' => Auth::user()->load('company'),
        ]);
    }

    public function saveFile(Request $request)
    {
        $request->validate([
            'executive_summary' => 'max:10240',
            'pitch_deck' => 'max:10240',
            'other_file_1' => 'max:10240',
            'other_file_2' => 'max:10240',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('company');

        if ($executiveSummary = $request->file('executive_summary')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($executiveSummary->path()));

            if ($user->company->executive_summary) {
                Storage::disk('files')->delete($user->company->executive_summary->id);
                $user->company->executive_summary->delete();
            }

            $user->company->executive_summary()->associate(File::create([
                'filename' => $executiveSummary->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id' => $user->getKey(),
            ]));

            $user->company->save();
        }

        if ($pitchDeck = $request->file('pitch_deck')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($pitchDeck->path()));

            if ($user->company->pitch_deck) {
                Storage::disk('files')->delete($user->company->pitch_deck->id);
                $user->company->pitch_deck->delete();
            }

            $user->company->pitch_deck()->associate(File::create([
                'filename' => $pitchDeck->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id' => $user->getKey(),
            ]));

            $user->company->save();
        }

        if ($otherFile1 = $request->file('other_file_1')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($otherFile1->path()));

            if ($user->company->other_file_1) {
                Storage::disk('files')->delete($user->company->other_file_1->id);
                $user->company->other_file_1->delete();
            }

            $user->company->other_file_1()->associate(File::create([
                'filename' => $otherFile1->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id' => $user->getKey(),
            ]));

            $user->company->save();
        }

        if ($otherFile2 = $request->file('other_file_2')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($otherFile2->path()));

            if ($user->company->other_file_2) {
                Storage::disk('files')->delete($user->company->other_file_2->id);
                $user->company->other_file_2->delete();
            }

            $user->company->other_file_2()->associate(File::create([
                'filename' => $otherFile2->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id' => $user->getKey(),
            ]));

            $user->company->save();
        }


        if ($executiveSummary || $pitchDeck || $otherFile1 || $otherFile2) {
            $request->session()->flash('message', 'Document uploaded.');
        }

        $bag = new MessageBag();

        if (!$user->company->executive_summary) {
            $bag->add('executive_summary', 'Please upload executive summary.');
        }

        if (!$user->company->pitch_deck) {
            $bag->add('pitch_deck', 'Please upload pitch deck.');
        }

        if ($bag->count()) {
            return redirect()->back()->with('errors', session()->get('errors', new ViewErrorBag)->put('default', $bag));
        }

        if ($user->company->current_step === 5) {
            $user->company->update(['current_step' => 6]);
        }

        return redirect('/participant/profile/submit');
    }

    public function editSubmit()
    {
        return view('participant.page.profile.submit', [
            'participant' => Auth::user()->load('company'),
        ]);
    }

    public function saveSubmit(Request $request)
    {
        $request->validate([
            'confirm_1' => 'required',
        ], [
            'confirm_1.required' => 'You must confirm that your application is complete, and the registration will be locked once submitted',
        ]);

        /** @var User $user */
        $user = Auth::user()->load('company');

        $user->company->submit = true;
        $user->company->save();

        return redirect('/participant/profile/submit');
    }
}
