<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class ImprovePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $showPrivate = Permission::create(['name' => 'show-private']);
        $managePermission = Permission::create(['name' => 'manage-permission']);
        $allCompetition = Permission::create(['name' => 'all-competitions']);

        Role::where('name', 'admin')->first()->perms()->saveMany([
            $showPrivate,
            $managePermission,
            $allCompetition,
        ]);

        Role::where('name', 'partner')->first()->perms()->saveMany([
            $showPrivate,
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
