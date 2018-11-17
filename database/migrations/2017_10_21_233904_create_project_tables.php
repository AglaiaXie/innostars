<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('disk_name');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });


        Schema::create('industries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('approval')->default(false);
            $table->boolean('submit')->default(false);
            $table->timestamps();
            //Company Info
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->foreign('logo_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('logo_id')->unsigned()->nullable();
            $table->string('type')->nullable();
            $table->string('size')->nullable();
            $table->string('established')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('United States');
            //Contact
            $table->string('contact_name')->nullable();
            $table->string('contact_title')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->integer('contact_photo_id')->unsigned()->nullable();
            $table->foreign('contact_photo_id')->references('id')->on('files')->onDelete('set null');
            //Project
            $table->integer('industry_id')->unsigned()->nullable();
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('set null');
            $table->string('project_name')->nullable();
            $table->text('project_description')->nullable();
            $table->string('project_stage')->nullable();
            $table->text('patents')->nullable();
            $table->string('cooperation')->nullable();
            $table->text('cooperation_alt')->nullable();
            //Addition
            $table->text('team_description')->nullable();
            $table->text('tech_requirement')->nullable();
            $table->text('tech_advantage')->nullable();
            $table->text('tech_risk')->nullable();
            $table->text('business_value')->nullable();
            //Document
            $table->integer('executive_summary_id')->unsigned()->nullable();
            $table->foreign('executive_summary_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('pitch_deck_id')->unsigned()->nullable();
            $table->foreign('pitch_deck_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('other_file_1_id')->unsigned()->nullable();
            $table->foreign('other_file_1_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('other_file_2_id')->unsigned()->nullable();
            $table->foreign('other_file_2_id')->references('id')->on('files')->onDelete('set null');
        });

        Schema::create('company_industry', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('industry_id')->unsigned();
            $table->foreign('industry_id')->references('id')->on('industries');
        });

        Schema::create('judge_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('approval')->default(false);
            $table->boolean('submit')->default(false);
            $table->timestamps();
            //Basic
            $table->string('company_name')->nullable();
            $table->string('position')->nullable();
            $table->string('title')->nullable();
            $table->string('phone')->nullable();
            $table->string('education')->nullable();
            //Addition
            $table->string('refer')->nullable();
            $table->text('experience')->nullable();
            //Document
            $table->integer('photo_file_id')->unsigned()->nullable();
            $table->foreign('photo_file_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('resume_file_id')->unsigned()->nullable();
            $table->foreign('resume_file_id')->references('id')->on('files')->onDelete('set null');
        });

        Schema::create('judging_industries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('industry_id')->unsigned();
            $table->foreign('industry_id')->references('id')->on('industries');
            $table->integer('judge_profile_id')->unsigned();
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles');
        });

        Schema::create('interested_industries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('industry_id')->unsigned();
            $table->foreign('industry_id')->references('id')->on('industries');
            $table->integer('judge_profile_id')->unsigned();
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles');
        });

        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('city')->nullable();
            $table->boolean('in_session')->default(false);
            $table->date('date');
        });

        Schema::create('competition_industries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competition_id')->unsigned();
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->integer('industry_id')->unsigned();
            $table->foreign('industry_id')->references('id')->on('industries');
        });

        Schema::create('joined_competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('competition_id')->unsigned();
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->boolean('approval')->default(false);
        });

        Schema::create('judging_competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('approved')->default(false);
            $table->integer('judge_profile_id')->unsigned();
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles');
            $table->integer('competition_id')->unsigned();
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->boolean('approval')->default(false);
        });

        Schema::create('scores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('joined_competition_id')->unsigned();
            $table->foreign('joined_competition_id')->references('id')->on('joined_competitions');
            $table->integer('judge_profile_id')->unsigned();
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles');
            $table->text('comment')->nullable();
            $table->integer('score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('scores');
        Schema::drop('judge_attends');
        Schema::drop('competitors');
        Schema::drop('competitions');
        Schema::drop('interested_industries');
        Schema::drop('judge_profiles');
        Schema::drop('companies');
        Schema::drop('industries');
    }
}
