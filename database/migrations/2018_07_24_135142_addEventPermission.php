<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddEventPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createEvent = Permission::create(['name' => 'create-event']);
        $overrideEvent = Permission::create(['name' => 'override-event']);

        Role::where('name', 'partner')->first()->perms()->saveMany([
            $createEvent,
        ]);

        Role::where('name', 'admin')->first()->perms()->saveMany([
            $overrideEvent,
        ]);
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
