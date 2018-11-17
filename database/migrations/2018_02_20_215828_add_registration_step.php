<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegistrationStep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('current_step')->after('submit')->default(1);
        });

        Schema::table('judge_profiles', function (Blueprint $table) {
            $table->integer('current_step')->after('submit')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('current_step');
        });

        Schema::table('judge_profiles', function (Blueprint $table) {
            $table->dropColumn('current_step');
        });
    }
}
