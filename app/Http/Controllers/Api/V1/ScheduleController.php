<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    use ScheduleTrait;

    public function index(Request $request)
    {
        $query = $this->filterByRequest($request);

        return $query->paginate();
    }

    protected function filterByRequest(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Schedule::with(['time_slot.event', 'users.company', 'users.investor_profile', 'users.judge_profile']);

        $type = $request->get('type');

        switch ($type) {
            case 'confirmed':
                $query->where('status', '=', Schedule::CONFIRMED)
                    ->whereHas('users', function (Builder $builder) use ($user) {
                        $builder->where('user_id', '=', $user->getKey());
                    });
                break;
            case 'received':
                $query->where('status', '=', Schedule::PENDING)
                    ->whereHas('users', function (Builder $builder) use ($user) {
                        $builder->where('user_id', '=', $user->getKey())
                            ->where('status', '=', Schedule::PENDING);
                    });
                break;
            case 'sent':
                $query->where('status', '=', Schedule::PENDING)
                    ->whereHas('users', function (Builder $builder) use ($user) {
                        $builder->where('user_id', '=', $user->getKey())
                            ->where('status', '=', Schedule::REQUESTED);
                    });
                break;
            case 'voided':
                $query->whereIn('status', [Schedule::DENIED, Schedule::CANCELED])
                    ->whereHas('users', function (Builder $builder) use ($user) {
                        $builder->where('user_id', '=', $user->getKey());
                    });;
                break;
        }

        if (($keyword = $request->get('keyword'))) {
            $query->where(function (Builder $builder) use ($keyword) {
                $builder->whereHas('time_slot.event', function (Builder $builder) use ($keyword) {
                    $builder->where('topic', 'LIKE', "%$keyword%");
                })
                ->orWhere('topic', 'LIKE', "%$keyword%");;
            });
        }

        if (($keyword = $request->get('adminKeyword'))) {
            $query->where(function (Builder $builder) use ($keyword) {
                $builder->whereHas('users.company', function (Builder $builder) use ($keyword) {
                    $builder->where('name', 'LIKE', "%$keyword%");
                })->orWhereHas('users', function (Builder $builder) use ($keyword) {
                    $builder->whereRaw("CONCAT_WS(' ', first_name, last_name) LIKE ?", ["%$keyword%"]);
                })->whereHas('users.investor_profile', function (Builder $builder) use ($keyword) {
                    $builder->where('company_name', 'LIKE', "%$keyword%");
                });
            });
        }

        if (($status = $request->get('status'))) {
            $query->where('status', '=', $status);
        }

        if (($event = $request->get('event'))) {
            $query->whereHas('time_slot.event', function (Builder $builder) use ($event) {
                $builder->where('id', '=', $event);
            });
        }

        return $query;
    }

    public function update(Schedule $schedule, Request $request)
    {
        $this->updateSchedule($schedule, $request);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
