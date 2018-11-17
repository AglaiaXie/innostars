<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddPartnerListSchedulePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $listSchedule = Permission::where(['name' => 'list-schedule'])->first();

        Role::where('name', 'partner')->first()->perms()->saveMany([
            $listSchedule,
        ]);

        $makSchedule = Permission::where(['name' => 'make-schedule'])->first();

        Role::where('name', 'partner')->first()->perms()->detach($makSchedule);
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
