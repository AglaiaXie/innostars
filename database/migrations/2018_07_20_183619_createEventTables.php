<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('competition_id')->unsigned();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->boolean('published')->default(false);
            $table->timestamps();
            //Basic
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
        });

        Schema::create('time_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            //Basic
            $table->integer('table_number');
            $table->text('note')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
        });

        Schema::create('event_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requester_id')->unsigned();
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('time_slot_id')->unsigned();
            $table->foreign('time_slot_id')->references('id')->on('time_slots')->onDelete('cascade');
            $table->timestamps();
            //Basic
            $table->string('name');
            $table->text('note');
            $table->boolean('confirmed');
        });

        Schema::create('schedule_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schedule_id')->unsigned();
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            //Basic
            $table->text('note');
            $table->enum('status', ['confirmed', 'denied', 'pending']);
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
