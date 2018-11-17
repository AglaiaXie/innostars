<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddSxswRoleAndAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = new Role();
        $role->name = 'sxsw_admin';
        $role->display_name = 'SXSW Admin';
        $role->save();

        /** @var User $user */
        $user = User::create([
            'first_name' => 'SXSW',
            'last_name'  => 'Admin',
            'email'      => 'sxsw@app',
            'password'   => bcrypt('sxswUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'sxsw_admin')->first());
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
