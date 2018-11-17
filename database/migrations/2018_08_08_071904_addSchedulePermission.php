<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddSchedulePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $makeSchedule = Permission::create(['name' => 'make-schedule']);

        Role::where('name', 'partner')->first()->perms()->saveMany([
            $makeSchedule,
        ]);

        Role::where('name', 'judge')->first()->perms()->saveMany([
            $makeSchedule,
        ]);

        Role::where('name', 'participant')->first()->perms()->saveMany([
            $makeSchedule,
        ]);

        Role::where('name', 'investor')->first()->perms()->saveMany([
            $makeSchedule,
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
