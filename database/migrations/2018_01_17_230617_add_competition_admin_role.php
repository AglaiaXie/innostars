<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddCompetitionAdminRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $judge = new Role();
        $judge->name = 'competition_admin';
        $judge->display_name = 'Competition Admin';
        $judge->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::where('name', 'competition_admin')->delete();
    }
}
