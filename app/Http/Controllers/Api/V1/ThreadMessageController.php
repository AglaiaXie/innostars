<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ThreadMessageController extends Controller
{
    public function index(Thread $thread)
    {
        $thread->markAsRead(Auth::id());
        return $thread->messages()->with('user')->orderBy('created_at', 'desc')->get();
    }

    public function store(Thread $thread, Request $request)
    {
        $thread->messages()->create([
            'user_id' => Auth::id(),
            'body' => $request->get('body'),
        ]);

        $thread->markAsRead(Auth::id());
        return response('', Response::HTTP_NO_CONTENT);
    }
}
