<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\JoinedCompetition;
use App\Models\Role;
use App\Models\SemifinalForm;
use App\Models\User;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use phpDocumentor\Reflection\Types\Object_;
use PragmaRX\Countries\Facade;
use SplTempFileObject;

class ParticipantController extends Controller
{
    protected $relations = [
        'company.industry',
        'company.joined_competitions.competition',
    ];

    protected $relations_show = [
        'company.industry',
        'company.sub_industry',
        'company.logo',
        'company.contact_photo',
        'company.executive_summary',
        'company.pitch_deck',
        'company.other_file_1',
        'company.other_file_2',
        'company.joined_competitions.competition',
    ];

    public function index(Request $request)
    {
        return $this->filterByRequest($request)->paginate($request->get('perPage'));
    }

    public function show(User $user)
    {
        return $user->load($this->relations_show);
    }

    public function all(Request $request)
    {
        $query = User::with($this->relations)->whereHas('roles', function ($query) {
                $query->where('name', 'participant');
            });

        $this->permissionCheck($query);

        if ($competition = $request->get('competition')) {
            $query->whereHas('company.joined_competitions', function (Builder $builder) use ($competition) {
                $builder->where('competition_id', $competition);
            });
        }

        if ($request->get('approved') === 'true') {
            $query->whereHas('company', function (Builder $builder) {
                $builder->where('approval', '=', 1);
            });
        }

        return $query->get();
    }

    public function edit(User $user)
    {
        $states = Facade::where('name.common', 'United States')
            ->first()
            ->states
            ->sortBy('name')
            ->pluck('name', 'postal');
        return view('admin.page.participant-edit', ['participant' => $user, 'states' => $states]);
    }

    public function update(User $user, Request $request)
    {
        $user->load('company');
        $user->company->update($request->all());

        /** @var JoinedCompetition $onlineCompetition */
        $onlineCompetition = $user->company->joined_competitions()->competitionType(Competition::NAME_ONLINE)->first();

        $onlineCompetition->approval = $user->company->approval;

        $onlineCompetition->save();

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('participant')) {
            $user->delete();

            return response('', Response::HTTP_NO_CONTENT);
        }

        return response('User is not an contestant account.', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Request $request
     * @throws \League\Csv\CannotInsertRecord
     */
    public function download(Request $request)
    {
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne([
            'Company Name',
            'Stage Participating In',
            'Website',
            'Contact Person Name',
            'Title',
            'Phone',
            'Email',
            'City',
            'State',
            'Industries of Focus',
            'Team Introduction',
            'Technology/Project Name',
            'Technology/Project Financing Stage',
            'Patent(s)',
            'Preferred Way of Cooperation',
            'How did you hear about us',
            'Pitch Deck',
            'Executive Summary',
            'Current Promoted Stage',
            'Semifinal Consent From',
            'Semifinal Flight Ticket Receipt',
            'Semifinal Registration Form',
            'Semifinal Pitch Deck',
            'Semifinal Executive Summary',
            'Final Flight Ticket Receipt',
            'Final Pitch Deck',
        ]);

        /** @var User $participant */
        foreach ($this->filterByRequest($request)->get() as $participant) {
            $currentPromoted = JoinedCompetition::query()
                ->join('competitions', 'competitions.id', '=', 'joined_competitions.competition_id')
                ->where('joined_competitions.company_id', '=', $participant->company->getkey())
                ->where('promoted', '=', 1)->orderBy('competitions.date', 'desc')
                ->get(['competitions.*'])->first();

            /** @var SemifinalForm $semifinalForm */
            $semifinalForm = $participant->semifinal_form;
            $finalForm = $participant->final_form;
            $semifinalConsentForm = object_get($semifinalForm, 'consent_form');
            $semifinalFlightTicketReceipt = object_get($semifinalForm, 'flight_ticket_receipt');
            $semifinalRegistrationFrom = object_get($semifinalForm, 'registration_form');
            $semifinalPitchDeck = object_get($semifinalForm, 'pitch_deck');
            $semifinalExecutiveSummary = object_get($semifinalForm, 'executive_summary');
            $finalFlightTicketReceipt = object_get($finalForm, 'flight_ticket_receipt');
            $finalPitchDeck = object_get($finalForm, 'pitch_deck');

            $record = [
                $participant->company->name,
                object_get($participant->company->joined_competitions()->whereHas('competition', function (Builder $builder) {
                    $builder->where('name', Competition::NAME_PRELIMINARY_STAGE);
                })
                    ->first(), 'competition.city'),
                $participant->company->website ? '=HYPERLINK("' . $participant->company->website . '")' : '',
                $participant->company->contact_name,
                $participant->company->contact_title,
                $participant->company->contact_phone,
                $participant->company->contact_email,
                $participant->company->city,
                $participant->company->state,
                object_get($participant, 'company.industry.name'),
                $participant->company->team_description,
                $participant->company->project_name,
                $participant->company->project_stage,
                $participant->company->patents,
                $participant->company->cooperation_alt ? $participant->company->cooperation . ', ' . $participant->company->cooperation_alt : $participant->company->cooperation,
                $participant->company->refer,
                $participant->company->pitch_deck ? '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $participant->company->pitch_deck->disk_name . '", "' . $participant->company->pitch_deck->filename . '")' : '',
                $participant->company->executive_summary ? '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $participant->company->executive_summary->disk_name . '", "' . $participant->company->executive_summary->filename . '")' : '',
                $currentPromoted ? $currentPromoted->name . ' - ' . $currentPromoted->city : '',
                $semifinalConsentForm ?  '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $semifinalConsentForm->disk_name . '", "' .$semifinalConsentForm->filename . '")' : '',
                $semifinalFlightTicketReceipt ?  '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $semifinalFlightTicketReceipt->disk_name . '", "' .$semifinalFlightTicketReceipt->filename . '")' : '',
                $semifinalRegistrationFrom ?  '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $semifinalRegistrationFrom->disk_name . '", "' .$semifinalRegistrationFrom->filename . '")' : '',
                $semifinalPitchDeck ?  '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $semifinalPitchDeck->disk_name . '", "' .$semifinalPitchDeck->filename . '")' : '',
                $semifinalExecutiveSummary ?  '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $semifinalExecutiveSummary->disk_name . '", "' .$semifinalExecutiveSummary->filename . '")' : '',
                $finalFlightTicketReceipt ?  '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $finalFlightTicketReceipt->disk_name . '", "' .$finalFlightTicketReceipt->filename . '")' : '',
                $finalPitchDeck ?  '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $finalPitchDeck->disk_name . '", "' .$finalPitchDeck->filename . '")' : '',
            ];

            if ($semifinalPeople = array_get($semifinalForm, 'semifinal_form_people')) {
                foreach ($semifinalPeople as $semifinalPerson) {
                    if ($file = object_get($semifinalPerson, 'passport')) {
                        $record[]  = '=HYPERLINK("https://innostars2018.uschinainnovation.org/file/' . $file->disk_name . '", "' .$file->filename . '")';
                    }
                }
            }

            $csv->insertOne($record);
        }

        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="contestants.csv"');
        header("Cache-control: private");

        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        echo $csv->getContent();
    }

    protected function filterByRequest(Request $request)
    {
        $query = User::with($this->relations)->whereHas('roles', function ($query) {
            $query->where('name', 'participant');
        });

        $this->permissionCheck($query);

        if ($request->get('sxsw') === 'true') {
            $query->where('sxsw', '=', true);
        }

        if ($industry = $request->get('industry')) {
            $query = $query->whereHas('company.industry', function (Builder $builder) use ($industry) {
                $builder->where('industry_id', $industry);
            });
        }

        if ($competition = $request->get('competition')) {
            $query->whereHas('company.joined_competitions', function (Builder $builder) use ($competition) {
                $builder->where('competition_id', $competition);
            });
        }

        if ($request->get('onlinePromoted') === 'true') {
            $query->whereHas('company.joined_competitions', function (Builder $builder) {
                $builder->where('promoted', '=', 1)->whereHas('competition', function (Builder $builder) {
                    $builder->where('name', '=', Competition::NAME_ONLINE);
                });
            });
        }

        if ($request->get('preliminaryPromoted') === 'true') {
            $query->whereHas('company.joined_competitions', function (Builder $builder) {
                $builder->where('promoted', '=', 1)->whereHas('competition', function (Builder $builder) {
                    $builder->where('name', '=', Competition::NAME_PRELIMINARY_STAGE);
                });
            });
        }

        if (($keyword = $request->get('keyword'))) {
            $query->where(function (Builder $builder) use ($keyword) {
                $builder->whereHas('company', function (Builder $builder) use ($keyword) {
                    $builder->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('contact_name', 'LIKE', "%$keyword%")
                        ->orWhere('contact_email', 'LIKE', "%$keyword%");
                })->orWhereRaw("CONCAT_WS(' ', first_name, last_name) LIKE ?", ["%$keyword%"])
                    ->orWhere('email', 'LIKE', "%$keyword%");
            });
        }

        if ($request->get('submitted') === 'true') {
            $query->whereHas('company', function (Builder $builder) {
                $builder->where('submit', '=', 1);
            });
        }

        if ($request->get('unsubmitted') === 'true') {
            $query->whereHas('company', function (Builder $builder) {
                $builder->where('submit', '=', 0);
            });
        }

        if ($request->get('approved') === 'true') {
            $query->whereHas('company', function (Builder $builder) {
                $builder->where('approval', '=', 1);
            });
        }

        $query->orderBy($request->get('sortBy', 'created_at'), $request->get('sortDirection', 'desc'));

        return $query;
    }

    protected function permissionCheck(Builder $query){
        /** @var User $user */
        $user = Auth::user();

        if (!$user->can('all-competitions')) {
            $limitCompetitionIds = $user->competitions();
            $query->whereHas('company.joined_competitions', function (Builder $builder) use ($limitCompetitionIds) {
                $builder->whereIn('competition_id', $limitCompetitionIds);
            });
        }

        if (!$user->can('show-private')) {
            $query->whereHas('company', function (Builder $builder) {
                $builder->where('approval', '=', 1);
            });
        }
    }
}
