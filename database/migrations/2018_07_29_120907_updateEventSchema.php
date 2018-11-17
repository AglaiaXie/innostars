<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('start');
            $table->dropColumn('end');
        });

        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropColumn('note');
            $table->integer('period')->after('table_number');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_requester_id_foreign');
            $table->dropColumn('requester_id');
            $table->dropColumn('name');
            $table->dropColumn('note');
            $table->dropColumn('confirmed');
            $table->enum('status', ['confirmed', 'pending', 'denied', 'canceled'])->after('time_slot_id')->nullable();
            $table->text('topic')->after('time_slot_id')->nullable();
        });

        Schema::table('schedule_user', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('note');
            $table->dropTimestamps();
        });

        Schema::table('schedule_user', function (Blueprint $table) {
            $table->enum('status', ['confirmed', 'pending', 'denied', 'canceled', 'requested'])->after('user_id')->nullable();
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
