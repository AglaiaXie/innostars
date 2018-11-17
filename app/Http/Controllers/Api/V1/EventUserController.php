<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Industry;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class EventUserController extends Controller
{
    public function store(Event $event, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $event->users()->sync($request->get('ids'));

        return response('', Response::HTTP_NO_CONTENT);
    }
}
