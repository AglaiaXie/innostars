<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\User;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    protected $relations = [
        'users.company',
        'users.judge_profile',
        'users.investor_profile',
        'users.partner_profile',
    ];

    public function index(Request $request)
    {
        return $this->filterByRequest($request)->paginate($request->get('perPage'));
    }

    protected function filterByRequest(Request $request)
    {
        $query = Thread::with($this->relations)->forUser(Auth::id());

        if (($keyword = $request->get('keyword'))) {
            $query->whereHas('messages', function (Builder $buider) use ($keyword) {
                $buider->where('body', 'LIKE', "%$keyword%");
            })->orWhere('subject', 'LIKE', "%$keyword%");
        }

        $query->has('users', '>', 1);

        $query->orderBy('threads.updated_at', 'desc');

        return $query;
    }
}
