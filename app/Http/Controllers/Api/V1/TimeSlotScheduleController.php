<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TimeSlotScheduleController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->schedules()->get();
    }

    public function store(TimeSlot $time_slot, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $userIds = [];

        if ($invitedId = $request->get('user_id')) {
            $userIds[] = $invitedId;
            $userIds[] = $user->getKey();
        }

        if ($users = $request->input('users')) {
            foreach ($users as $user) {
                $userIds[] = $user['id'];
            }
        }

        /** @var Schedule $existingSchedule */
        $existingSchedule = $time_slot->event->schedules()
            ->whereIn('status', [Schedule::CONFIRMED, Schedule::PENDING, Schedule::DENIED])
            ->whereHas('users', function (Builder $builder) use ($userIds) {
                $builder->where('user_id', '=', $userIds[0]);
            })->whereHas('users', function (Builder $builder) use ($userIds) {
                $builder->where('user_id', '=', $userIds[1]);
            })->first();

        if ($existingSchedule) {
            return response(
                'Create schedule failed, there is a ' . $existingSchedule->status . ' schedule at table #' .
                $existingSchedule->time_slot->table_number . ' from ' . $existingSchedule->time_slot->start .
                ' to ' . $existingSchedule->time_slot->end,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($invitedId) {
            /** @var Schedule $schedule */
            $schedule = $time_slot->schedules()->create($request->all() + ['status' => 'pending']);
            $schedule->users()->attach($user->getKey(), ['status' => 'requested']);
            $schedule->users()->attach($invitedId, ['status' => 'pending']);
        } else {
            /** @var Schedule $schedule */
            $schedule = $time_slot->schedules()->create($request->all() + ['status' => 'confirmed']);
            $schedule->users()->attach($userIds[0], ['status' => 'confirmed']);
            $schedule->users()->attach($userIds[1], ['status' => 'confirmed']);
        }

        return response('', Response::HTTP_NO_CONTENT);
    }
}
