<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\User;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        /** @var Thread $thread */
        $thread = Thread::create([
            'subject' => $request->get('title'),
        ]);

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $request->get('body'),
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

        //Mail::to($recipient->email)
        //    ->send(new NewMessage($request->get('title'), $recipient->first_name . ' ' . $recipient->last_name));

        return response('', Response::HTTP_NO_CONTENT);
    }
}
