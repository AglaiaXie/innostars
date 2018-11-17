<?php

use App\Models\Competition;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddCompetitionAdminUser extends Migration
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
            'first_name' => 'Atlanta',
            'last_name'  => 'Admin',
            'email'      => 'atlanta@app',
            'password'   => bcrypt('atlantaUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'competition_admin')->first());

        /** @var Competition $competition */
        $competition = Competition::where('city', 'Atlanta')->first();

        $competition->admin()->associate($user);
        $competition->save();

        $user = User::create([
            'first_name' => 'Chicago',
            'last_name'  => 'Admin',
            'email'      => 'chicago@app',
            'password'   => bcrypt('chicagoUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'competition_admin')->first());

        /** @var Competition $competition */
        $competition = Competition::where('city', 'Chicago')->first();

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
        User::whereIn('email', ['atlanta@app', 'chicago@app'])->delete();
    }
}
