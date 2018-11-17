<?php namespace App\Http\Controllers;

use App\Mail\NewMessage;
use App\Mail\NewReply;
use App\Models\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JudgeMessageController extends Controller
{
    public function index()
    {
        return view('judge.page.message', [
            'judge'   => Auth::user()->load('judge_profile'),
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
            'user_id'   => Auth::id(),
            'body'      => $input['message'],
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id'   => Auth::id(),
            'last_read' => new Carbon,
        ]);

        // Recipients
        if ($uid = $request->get('uid')) {
            $recipient = User::find($uid);

            $thread->addParticipant($uid);
        } else {
            /** @var User $recipient */
            $recipient = User::withRole('admin')->first();

            $thread->addParticipant($recipient->getKey());
        }


        Mail::to($recipient->email)
            ->send(new NewMessage($input['subject'], $recipient->first_name . ' ' . $recipient->last_name));

        return redirect('/judge/threads');
    }

    public function show(Thread $thread)
    {
        $thread->markAsRead(Auth::id());

        return $thread->load('messages.user');
    }

    public function update(Thread $thread, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $thread->activateAllParticipants();

        $input = $request->all();
        Message::create([
            'thread_id' => $thread->id,
            'user_id'   => $user->getKey(),
            'body'      => $input['message'],
        ]);

        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id'   => $user->getKey(),
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

        return redirect('/judge/threads');
    }
}