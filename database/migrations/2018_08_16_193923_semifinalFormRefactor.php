<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SemifinalFormRefactor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('semifinal_forms')->delete();
        Schema::drop('semifinal_forms');

        Schema::create('semifinal_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->timestamps();

            $table->integer('executive_summary_id')->unsigned()->nullable();
            $table->foreign('executive_summary_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('flight_ticket_receipt_id')->unsigned()->nullable();
            $table->foreign('flight_ticket_receipt_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('pitch_deck_id')->unsigned()->nullable();
            $table->foreign('pitch_deck_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('registration_form_id')->unsigned()->nullable();
            $table->foreign('registration_form_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('consent_form_id')->unsigned()->nullable();
            $table->foreign('consent_form_id')->references('id')->on('files')->onDelete('set null');
        });

        Schema::create('semifinal_form_people', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('semifinal_form_id')->unsigned();
            $table->foreign('semifinal_form_id')->references('id')->on('semifinal_forms')->onDelete('cascade');
            $table->string('name');
            $table->integer('passport_id')->unsigned()->nullable();
            $table->foreign('passport_id')->references('id')->on('files')->onDelete('set null');
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
