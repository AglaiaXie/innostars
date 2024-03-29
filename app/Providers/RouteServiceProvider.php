<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\JoinedCompetition;
use App\Models\JudgingCompetition;
use App\Models\Score;
use App\Models\SemifinalForm;
use App\Models\SemifinalFormPerson;
use App\Models\Thread;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::model('participant', User::class);
        Route::model('judge', User::class);
        Route::model('investor', User::class);
        Route::model('partner', User::class);
        Route::model('company', JoinedCompetition::class);
        Route::model('judging', JudgingCompetition::class);
        Route::model('score', Score::class);
        Route::model('user', User::class);
        Route::model('message', Thread::class);
        Route::model('thread', Thread::class);
        Route::model('event', Event::class);
        Route::model('time_slot', TimeSlot::class);
        Route::model('semifinal_form', SemifinalForm::class);
        Route::model('semifinal_form_person', SemifinalFormPerson::class);

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api/v1')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
