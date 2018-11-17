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

class EventTimeSlotController extends Controller
{
    public function store(Event $event, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $startDates = [$firstDate = Carbon::parse($request->get('start'))->timezone(Config::get('app.timezone'))];

        if ($request->get('repeatSchedule') === true) {
            $weekDayOnly = $request->get('weekDayOnly') === true;
            for ($i = 1; $i <= $request->get('repeatDays', 1); $i++) {
                $startDates[] = $weekDayOnly ? (clone $firstDate)->addWeekdays($i) : (clone $firstDate)->addDays($i);
            }
        }

        $tables = $request->get('tables');
        $meetings = $request->get('meetings');
        $interval = $request->get('interval');
        $lunchBreak = $request->get('lunchBreak');
        $breakAfter = $request->get('breakAfter');
        $breakPeriod = $request->get('breakPeriod');
        $period = 0;

        /** @var Carbon $startDate */
        foreach ($startDates as $startDate) {
            for ($meeting = 0; $meeting < $meetings; $meeting++) {
                $period++;
                for ($table = 1; $table <= $tables; $table++) {
                    $afterLunchBreak = $meeting + 1 > $breakAfter;
                    $start = (clone $startDate)->addMinutes($interval * $meeting + ($afterLunchBreak ? (int)$lunchBreak * $breakPeriod : 0));
                    $end = (clone $start)->addMinutes($interval);
                    $event->time_slots()->create([
                        'table_number' => $table,
                        'period' => $period,
                        'start' => $start,
                        'end' => $end,
                    ]);
                }
            }
        }

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function days(Event $event)
    {
        $days = [];

        /** @var TimeSlot $timeSlot */
        $event->time_slots()->with(['schedules.users.company', 'schedules.users.judge_profile', 'schedules.users.investor_profile'])
            ->orderBy('start', 'asc')->get()->each(function ($timeSlot) use (&$days) {
                $days[$timeSlot->start->format('m/d/Y')][$timeSlot->table_number][] = $timeSlot->toArray() + [
                    'from' => $timeSlot->start->format('H:i'),
                    'to' => $timeSlot->end->format('H:i'),
                ];
        });

        return $days;
    }

    public function deleteDay(Event $event, Request $request)
    {
        $day = new Carbon($request->get('date'));
        $end = (new Carbon($request->get('date')))->endOfDay();
        $event->time_slots()->whereBetween('start', [$day, $end])->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function index(Event $event, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $bookedPeriod = $event->time_slots()->whereHas('schedules', function (Builder $builder) use ($user) {
            $builder->whereIn('status', ['pending', 'confirmed'])->whereHas('users', function (Builder $builder) use ($user) {
                $builder->where('user_id', '=', $user->getKey());
            });
        })->pluck('id');

        $type = $request->get('type');

        /** @var Schedule $existing */
        $existing = Schedule::find($request->get('scheduled_id'));

        return $event->time_slots()->whereDoesntHave('schedules', function (Builder $builder) use ($type, $existing) {
            if ($type === 'available') {
                $builder->whereIn('status', ['confirmed', 'pending']);
            }

            if ($existing) {
                $builder->where('id', '<>', $existing->time_slot_id);
            }
        })->whereNotIn('id', $bookedPeriod)->get();
    }

    public function destroy(Event $event, TimeSlot $timeSlot)
    {
        $timeSlot->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
