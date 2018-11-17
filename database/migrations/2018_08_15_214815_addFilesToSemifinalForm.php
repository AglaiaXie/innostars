<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFilesToSemifinalForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('semifinal_forms', function (Blueprint $table) {
            $table->integer('executive_summary_id')->unsigned()->nullable();
            $table->foreign('executive_summary_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('pitch_deck_id')->unsigned()->nullable();
            $table->foreign('pitch_deck_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('passport_id')->unsigned()->nullable();
            $table->foreign('passport_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('visa_id')->unsigned()->nullable();
            $table->foreign('visa_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('consent_id')->unsigned()->nullable();
            $table->foreign('consent_id')->references('id')->on('files')->onDelete('set null');
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
