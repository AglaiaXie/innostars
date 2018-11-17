<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddIndustryAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = new Role();
        $role->name = 'industry_admin';
        $role->display_name = 'Industry Admin';
        $role->save();

        /** @var User $user */
        $user = User::create([
            'first_name' => 'Information and Communication Technology',
            'last_name'  => 'Admin',
            'email'      => 'it@app',
            'password'   => bcrypt('itUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'industry_admin')->first());

        /** @var User $user */
        $user = User::create([
            'first_name' => 'Artificial Intelligence and Augmented/Virtual Reality',
            'last_name'  => 'Admin',
            'email'      => 'ai@app',
            'password'   => bcrypt('aiUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'industry_admin')->first());

        /** @var User $user */
        $user = User::create([
            'first_name' => 'Advanced Manufacturing',
            'last_name'  => 'Admin',
            'email'      => 'mfr@app',
            'password'   => bcrypt('mfrUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'industry_admin')->first());

        /** @var User $user */
        $user = User::create([
            'first_name' => 'Environmental Technology',
            'last_name'  => 'Admin',
            'email'      => 'env@app',
            'password'   => bcrypt('envUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'industry_admin')->first());

        /** @var User $user */
        $user = User::create([
            'first_name' => 'Renewable Energy and New Materials',
            'last_name'  => 'Admin',
            'email'      => 'nrg@app',
            'password'   => bcrypt('nrgUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'industry_admin')->first());

        /** @var User $user */
        $user = User::create([
            'first_name' => 'Healthcare and Biotechnology',
            'last_name'  => 'Admin',
            'email'      => 'hb@app',
            'password'   => bcrypt('hbUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'industry_admin')->first());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::whereIn('email', ['sxsw@app'])->delete();

        Role::where('name', 'sxsw_admin')->delete();
    }
}
