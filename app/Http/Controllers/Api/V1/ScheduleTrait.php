<?php namespace App\Http\Controllers\Api\V1;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait ScheduleTrait {
    protected function updateSchedule(Schedule $schedule, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $status = $request->get('status');

        $schedule->users()->where('user_id', '=', $user->getKey())
            ->update(['status' => $status]);

        $schedule->update(['status' => $status]);

    }
}