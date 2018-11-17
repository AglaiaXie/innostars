<?php namespace App\Http\Controllers;

use App\Mail\NewMessage;
use App\Mail\NewReply;
use App\Models\Company;
use App\Models\Industry;
use App\Models\JudgeProfile;
use App\Models\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CommonMessageController extends Controller
{
    public function index()
    {
        return view('common.page.message', [
            'partial' => 'inbox',
            'user' => Auth::user(),
            'threads' => Thread::forUser(Auth::id())->get()->sortByDesc('latest_message.created_at'),
        ]);
    }

    public function show(Thread $thread)
    {
        $thread->markAsRead(Auth::id());

        return $thread->load('messages.user');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var Thread $thread */
        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $input['message'],
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);

        // Recipients

        /** @var User $recipient */

        $recipient = User::find($request->get('uid'));
        $thread->addParticipant($recipient->getKey());

        Mail::to($recipient->email)
            ->send(new NewMessage($input['subject'], $recipient->first_name . ' ' . $recipient->last_name));

        return redirect('/common/messages');
    }

    public function update(Thread $thread, Request $request) {
        /** @var User $user */
        $user = Auth::user();

        $thread->activateAllParticipants();

        $input = $request->all();

        Message::create([
            'thread_id' => $thread->id,
            'user_id' => $user->getkey(),
            'body' => $input['message'],
        ]);
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        /** @var Participant $participant */
        foreach ($thread->participants as $participant) {
            if ($participant->user->getKey() !== $user->getKey()) {
                Mail::to($participant->user->email)
                    ->send(new NewReply($thread->subject, $user->first_name . ' ' . $user->last_name));
            }
        }

        return redirect('/common/messages');
    }

    public function participants(Request $request)
    {
        $query = Company::where('approval', 1)->whereHas('user', function (Builder $builder) {
            $builder->where('id', '<>', Auth::id());
        });

        if ($industry = $request->get('industry')) {
            $query->where('industry_id', $industry);
        }

        $companies = $query->get();

        if (($keyword = $request->get('keyword'))) {
            $companies = $companies->filter(function ($company) use ($keyword) {
                return stripos($company->name, $keyword) > -1 ||
                    stripos($company->contact_name, $keyword) > -1;
            });
        }

        return view('common.page.message', [
            'partial' => 'company',
            'user' => Auth::user(),
            'companies' => $companies,
            'industries' => Industry::all(),
            'industry_f' => $industry,
        ]);
    }

    public function judges(Request $request)
    {
        $query = JudgeProfile::where('approval', 1)->whereHas('user', function (Builder $builder) {
            $builder->where('id', '<>', Auth::id());
        });

        if ($industry = $request->get('industry')) {
            $query->whereHas('judging_industries', function (Builder $builder) use ($industry) {
                $builder->where('industry_id', $industry);
            });
        }

        $judges = $query->get();

        if (($keyword = $request->get('keyword'))) {
            $judges = $judges->filter(function ($judge) use ($keyword) {
                return stripos($judge->company_name, $keyword) > -1 ||
                    stripos($judge->user->first_name . ' ' . $judge->user->last_name, $keyword) > -1;
            });
        }

        return view('common.page.message', [
            'partial' => 'judge',
            'user' => Auth::user(),
            'judges' => $judges,
            'industries' => Industry::all(),
            'industry_f' => $industry,
        ]);
    }

    public function admins()
    {
        return view('common.page.message', [
            'partial' => 'admin',
            'user' => Auth::user(),
            'admin' => User::whereHas('roles', function (Builder $builder) {
                $builder->where('name', 'admin');
            })->first(),
        ]);
    }
}
