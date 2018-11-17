<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $listParticipant = Permission::create(['name' => 'list-participant']);
        $exportParticipant = Permission::create(['name' => 'export-participant']);
        $deleteParticipant = Permission::create(['name' => 'delete-participant']);
        $listJudge = Permission::create(['name' => 'list-judge']);
        $exportJudge = Permission::create(['name' => 'export-judge']);
        $deleteJudge = Permission::create(['name' => 'delete-judge']);
        $listInvestor = Permission::create(['name' => 'list-investor']);
        $exportInvestor = Permission::create(['name' => 'export-investor']);
        $deleteInvestor = Permission::create(['name' => 'delete-investor']);
        $listPartner = Permission::create(['name' => 'list-partner']);
        $exportPartner = Permission::create(['name' => 'export-partner']);
        $deletePartner = Permission::create(['name' => 'delete-partner']);
        $listCompetition = Permission::create(['name' => 'list-competition']);
        $listMessage = Permission::create(['name' => 'list-message']);
        $accessProfile = Permission::create(['name' => 'access-profile']);

        Permission::whereIn('name', ['join-competition', 'add-Project', 'score-Project', 'browse-Project'])->delete();

        Role::where('name', 'admin')->first()->perms()->saveMany([
            $listParticipant,
            $exportParticipant,
            $deleteParticipant,
            $listJudge,
            $exportJudge,
            $deleteJudge,
            $listInvestor,
            $exportInvestor,
            $deleteInvestor,
            $listPartner,
            $exportPartner,
            $deletePartner,
            $listCompetition,
            $listMessage,
        ]);

        Role::where('name', 'read_admin')->first()->perms()->saveMany([
            $listParticipant,
            $exportParticipant,
            $listJudge,
            $exportJudge,
            $listInvestor,
            $exportInvestor,
            $listPartner,
            $exportPartner,
            $listCompetition,
            $listMessage,
        ]);

        Role::where('name', 'competition_admin')->first()->perms()->saveMany([
            $listParticipant,
            $deleteParticipant,
            $exportParticipant,
            $listJudge,
            $deleteJudge,
            $exportJudge,
            $listCompetition,
            $listMessage,
        ]);

        Role::where('name', 'sxsw_admin')->first()->perms()->saveMany([
            $listParticipant,
            $deleteParticipant,
            $exportParticipant,
            $listJudge,
            $deleteJudge,
            $exportJudge,
            $listCompetition,
            $listMessage,
        ]);

        Role::where('name', 'industry_admin')->first()->perms()->saveMany([
            $listParticipant,
            $deleteParticipant,
            $exportParticipant,
            $listJudge,
            $deleteJudge,
            $exportJudge,
            $listCompetition,
            $listMessage,
        ]);

        Role::where('name', 'judge')->first()->perms()->saveMany([$accessProfile]);

        Role::where('name', 'participant')->first()->perms()->saveMany([$accessProfile]);

        Role::create(['name' => 'investor', 'display_name' => 'Investor'])->perms()->saveMany([
            $listParticipant,
            $exportParticipant,
            $listJudge,
            $deleteJudge,
            $exportJudge,
            $listMessage,
            $accessProfile,
        ]);

        Role::create(['name' => 'partner', 'display_name' => 'Partner'])->perms()->saveMany([
            $listParticipant,
            $exportParticipant,
            $listJudge,
            $exportJudge,
            $listMessage,
            $accessProfile,
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
