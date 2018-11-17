<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Industry;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    protected $relations = [
        'user',
    ];

    protected $relations_show = [
        'users.company.industry',
        'users.judge_profile.judging_industries',
        'users.investor_profile.interested_industries',
        'user',
        'time_slots',
        'competition'
    ];

    public function index(Request $request)
    {
        if ($request->get('filterBy') === 'allEvents') {
            return Event::all();
        }

        return $this->filterByRequest($request)->paginate($request->get('perPage'));
    }

    public function show(Event $event)
    {
        return $event->load($this->relations_show);
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $event = Event::create($request->all() + ['user_id' => $user->getKey()]);

        return $event;
    }

    public function update(Event $event, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $event->update($request->all());

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function destroy(Event $event)
    {
        /** @var User $user */
        $user = Auth::user();

        $event->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }

    protected function filterByRequest(Request $request)
    {
        $query = Event::with($this->relations);

        $this->permissionCheck($query);

        if ($competition = $request->get('competition')) {
            $query->where('competition_id', $competition);
        }

        if (($keyword = $request->get('keyword'))) {
            $query->where('name', 'LIKE', "%$keyword%");
        }

        $query->orderBy($request->get('sortBy'), $request->get('sortDirection'));

        return $query;
    }

    protected function permissionCheck(Builder $query){
        /** @var User $user */
        $user = Auth::user();

        switch ($user->type) {
            case 'investor':
            case 'judge':
            case 'participant':
                $query->where('published','=', 1)
                    ->whereIn('id', $user->events()->pluck('events.id'));
                break;
            case 'partner':
                $query->where('user_id', '=', $user->getKey());
        }

        if (!$user->can('all-competitions')) {
            $limitCompetitionIds = $user->competitions();
            $query->whereIn('competition_id', $limitCompetitionIds);
        }

        return $query;
    }
}
