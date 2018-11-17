<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddDefaultUsers extends Migration
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
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'email'      => 'admin@app',
            'password'   => bcrypt('secret'),
        ]);

        $user->roles()->save(Role::where('name', 'admin')->first());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::where('email', 'admin@app')->delete();
    }
}
