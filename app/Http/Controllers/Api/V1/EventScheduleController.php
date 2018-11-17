<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Industry;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class EventScheduleController extends Controller
{
    use ScheduleTrait;

    public function index(Event $event)
    {
        /** @var User $user */
        $user = Auth::user();

        return $event->schedules()->with([
            'time_slot',
            'users.company',
            'users.investor_profile',
            'users.judge_profile',
        ])->whereHas('users', function (Builder $builder) use ($user) {
            $builder->where('user_id', '=', $user->getKey());
        })->get();
    }

    public function update(Event $event, Schedule $schedule, Request $request)
    {
        if ($request->get('status')) {
            $this->updateSchedule($schedule, $request);
        }

        $schedule->update($request->except('status'));

        return response('', Response::HTTP_NO_CONTENT);
    }
}
