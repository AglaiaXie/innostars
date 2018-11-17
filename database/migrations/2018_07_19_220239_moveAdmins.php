<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Migrations\Migration;

class MoveAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $partnerRole = Role::where('name', 'new_partner')->first();
        User::whereHas('roles', function (Builder $builder) {
            $builder->whereIn('name', ['competition_admin', 'sxsw_admin', 'industry_admin', 'read_admin']);
        })->get()->each(function ($user) use ($partnerRole) {
            $user->roles()->sync([$partnerRole->id]);
            $user->partner_profile()->create([]);
        });
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
