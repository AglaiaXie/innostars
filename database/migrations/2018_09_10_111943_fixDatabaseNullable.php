<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixDatabaseNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('industries', function (Blueprint $table) {
            $table->string('name_cn')->nullable()->change();
        });

        Schema::table('sub_industries', function (Blueprint $table) {
            $table->string('name_cn')->nullable()->change();
        });

        Schema::table('investor_profiles', function (Blueprint $table) {
            $table->string('linkedin')->nullable()->change();
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
