<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AdjustPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $accessProfile = Permission::where('name', 'access-profile')->first();
        $listParticipant = Permission::where('name', 'list-participant')->first();
        $exportParticipant = Permission::where('name', 'export-participant')->first();
        $listJudge = Permission::where('name', 'list-judge')->first();
        $exportJudge = Permission::where('name', 'export-judge')->first();
        $listMessage = Permission::where('name', 'list-message')->first();
        $listInvestor = Permission::where('name', 'list-investor')->first();
        $listPartner = Permission::where('name', 'list-partner')->first();

        $updateParticipant = Permission::create(['name' => 'update-participant']);
        $loginParticipant = Permission::create(['name' => 'login-participant']);
        $updateJudge = Permission::create(['name' => 'update-judge']);
        $loginJudge = Permission::create(['name' => 'login-judge']);
        $updateInvestor = Permission::create(['name' => 'update-investor']);
        $loginInvestor = Permission::create(['name' => 'login-investor']);
        $updatePartner = Permission::create(['name' => 'update-partner']);
        $loginPartner = Permission::create(['name' => 'login-partner']);
        $updateCompetition = Permission::create(['name' => 'update-competition']);


        Role::create(['name' => 'new_investor', 'display_name' => 'New Investor'])->perms()->saveMany([
            $accessProfile,
        ]);

        Role::create(['name' => 'new_partner', 'display_name' => 'New Partner'])->perms()->saveMany([
            $accessProfile,
        ]);

        Role::where('name', 'admin')->first()->perms()->saveMany([
            $updateParticipant,
            $loginParticipant,
            $updateJudge,
            $loginJudge,
            $updateInvestor,
            $loginInvestor,
            $updatePartner,
            $loginPartner,
            $updateCompetition,
        ]);

        Role::where('name', 'sxsw_admin')->first()->perms()->saveMany([
            $updateParticipant,
            $updateJudge,
        ]);

        Role::where('name', 'industry_admin')->first()->perms()->saveMany([
            $updateParticipant,
            $updateJudge,
        ]);

    Role::where('name', 'investor')->first()->perms()->sync([
            $listParticipant->getKey(),
            $listJudge->getKey(),
            $listInvestor->getKey(),
            $listMessage->getKey(),
            $accessProfile->getKey(),
        ]);

        Role::where('name', 'partner')->first()->perms()->sync([
            $listParticipant->getKey(),
            $exportParticipant->getKey(),
            $listJudge->getKey(),
            $exportJudge->getKey(),
            $listPartner->getKey(),
            $listInvestor->getKey(),
            $listMessage->getKey(),
            $accessProfile->getKey(),
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
