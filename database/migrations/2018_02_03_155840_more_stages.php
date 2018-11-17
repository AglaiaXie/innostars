<?php

use App\Models\Competition;
use App\Models\Industry;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class MoreStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_ONLINE,
            'city' => 'Online',
            'date' => Carbon::createFromDate(2018, 1, 1),
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_AI,
            Industry::NAME_ENV,
            Industry::NAME_ENERGY,
            Industry::NAME_IT,
            Industry::NAME_HEALTHCARE,
            Industry::NAME_MANUFACTURING
        ])->get());

        $competition->save();


        /** @var User $user */
        $user = User::create([
            'first_name' => 'Denver',
            'last_name'  => 'Admin',
            'email'      => 'denver@app',
            'password'   => bcrypt('denverUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'competition_admin')->first());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_PRELIMINARY_STAGE,
            'city' => 'Denver',
            'date' => null,
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_HEALTHCARE,
            Industry::NAME_IT,
            Industry::NAME_ENERGY
        ])->get());

        $competition->admin()->associate($user);
        $competition->save();

        $user = User::create([
            'first_name' => 'San Francisco',
            'last_name'  => 'Admin',
            'email'      => 'sf@app',
            'password'   => bcrypt('sfUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'competition_admin')->first());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_PRELIMINARY_STAGE,
            'city' => 'San Francisco',
            'date' => Carbon::createFromDate(2018, 5, 22),
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_AI,
            Industry::NAME_ENV,
            Industry::NAME_ENERGY,
            Industry::NAME_IT,
        ])->get());

        $competition->admin()->associate($user);
        $competition->save();

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => 'Shenzhen',
            'date' => Carbon::createFromDate(2018, 9, 10),
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_IT,
        ])->get());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => 'Dalian',
            'date' => Carbon::createFromDate(2018, 9, 10),
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_HEALTHCARE,
        ])->get());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => 'Foshan',
            'date' => Carbon::createFromDate(2018, 9, 10),
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_MANUFACTURING,
        ])->get());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => 'Ningbo',
            'date' => null,
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_ENERGY,
        ])->get());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => 'Xi\'an',
            'date' => null,
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_ENERGY,
        ])->get());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => 'Suzhou',
            'date' => null,
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_HEALTHCARE,
        ])->get());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => 'Chengdu',
            'date' => null,
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_HEALTHCARE,
        ])->get());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => 'Hebei',
            'date' => null,
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_HEALTHCARE,
        ])->get());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => 'Hangzhou',
            'date' => null,
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_AI,
        ])->get());

        /** @var Competition $competition */
        $competition = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => 'Tianjin',
            'date' => null,
        ]);

        $competition->industries()->saveMany(Industry::whereIn('name', [
            Industry::NAME_IT,
        ])->get());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Competition::where('name', Competition::NAME_SEMI_FINAL)->delete();

        Competition::where('name', Competition::NAME_PRELIMINARY_STAGE)->whereIn('city', ['Denver', 'San Francisco'])->delete();

        User::whereIn('email', ['denver@app', 'sf@app'])->delete();
    }
}
