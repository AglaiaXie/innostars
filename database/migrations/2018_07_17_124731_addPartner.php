<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->boolean('approval')->default(false);
            $table->boolean('submit')->default(false);
            $table->integer('current_step')->default(1);
            $table->timestamps();
            //Basic
            $table->string('company_name')->nullable();
            $table->text('company_description')->nullable();
            $table->string('title')->nullable();
            $table->string('phone')->nullable();
            $table->text('reason')->nullable();
            //Document
            $table->integer('logo_file_id')->unsigned()->nullable();
            $table->foreign('logo_file_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('document_file_id')->unsigned()->nullable();
            $table->foreign('document_file_id')->references('id')->on('files')->onDelete('set null');
        });

        Schema::create('partner_competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('partner_profile_id')->unsigned();
            $table->foreign('partner_profile_id')->references('id')->on('partner_profiles')->onDelete('cascade');;
            $table->integer('competition_id')->unsigned();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');;
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
