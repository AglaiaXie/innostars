<?php namespace App\Http\Controllers;

use App\Mail\NewMessage;
use App\Mail\NewReply;
use App\Models\Competition;
use App\Models\JoinedCompetition;
use App\Models\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ParticipantMessageController extends Controller
{
    public function index()
    {
        return view('participant.page.message', [
            'participant' => Auth::user()->load('company.joined_competitions.competition'),
            'threads' => Thread::forUser(Auth::id())->get()->sortByDesc('latest_message.created_at'),
        ]);
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
        /** @var User $participant */
        $participant = Auth::user();

        /** @var JoinedCompetition $joinedPreliminary */
        $joinedPreliminary = $participant->company->joined_competitions()->competitionType(Competition::NAME_PRELIMINARY_STAGE)->first();

        /** @var User $recipient */
        if ($uid = $request->get('uid')) {
            $recipient = User::find($uid);

            $thread->addParticipant($uid);
        } else {
            $recipient = object_get($joinedPreliminary, 'competition.admin') ? : User::withRole('admin')->first();

            $thread->addParticipant($recipient->getKey());
        }

        Mail::to($recipient->email)
            ->send(new NewMessage($input['subject'], $recipient->first_name . ' ' . $recipient->last_name));

        return redirect('/participant/threads');
    }

    public function show(Thread $thread)
    {
        $thread->markAsRead(Auth::id());
        
        return $thread->load('messages.user');
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

        return redirect('/participant/threads');
    }
}