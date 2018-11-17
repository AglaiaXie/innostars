<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddReadonlyAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = new Role();
        $role->name = 'read_admin';
        $role->display_name = 'Read Admin';
        $role->save();

        /** @var User $user */
        $user = User::create([
            'first_name' => 'Read Only',
            'last_name'  => 'Admin',
            'email'      => 'read@app',
            'password'   => bcrypt('readUCIA'),
        ]);

        $user->roles()->save(Role::where('name', 'read_admin')->first());
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
