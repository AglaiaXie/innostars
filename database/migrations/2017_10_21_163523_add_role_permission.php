<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddRolePermission extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        //Competitor permission
        $joinCompetition = new Permission();
        $joinCompetition->name = 'join-competition';
        $joinCompetition->save();

        $addProject = new Permission();
        $addProject->name = 'add-Project';
        $addProject->save();

        $competitor = new Role();
        $competitor->name = 'participant';
        $competitor->display_name = 'Competitor';
        $competitor->save();
        $competitor->perms()->saveMany([
            $joinCompetition,
            $addProject
        ]);

        //Judge permission
        $scoreProject = new Permission();
        $scoreProject->name = 'score-Project';
        $scoreProject->save();

        $browseProject = new Permission();
        $browseProject->name = 'browse-Project';
        $browseProject->save();

        $judge = new Role();
        $judge->name = 'judge';
        $judge->display_name = 'Judge';
        $judge->save();
        $judge->perms()->saveMany([
            $scoreProject,
            $browseProject,
        ]);

        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'Administrator';
        $admin->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::table('permission_role')->delete();
        DB::table('permissions')->delete();
        DB::table('roles')->delete();
    }
}
