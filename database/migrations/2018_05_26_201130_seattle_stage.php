<?php

use App\Models\Competition;
use App\Models\Industry;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class SeattleStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var User $user */
        $user = User::create([
            'first_name' => 'Seattle',
            'last_name'  => 'Admin',
            'email'      => 'seattle@app',
            'password'   => bcrypt('seattleUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'competition_admin')->first());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_PRELIMINARY_STAGE,
            'city' => 'Seattle',
            'date' => Carbon::createFromDate(2018, 7, 11),
            'deadline' => Carbon::createFromDate(2018, 7, 1),
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_AI,
            Industry::NAME_ENV,
            Industry::NAME_ENERGY,
            Industry::NAME_IT,
            Industry::NAME_HEALTHCARE,
            Industry::NAME_MANUFACTURING
        ])->get());

        $competition->admin()->associate($user);
        $competition->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
