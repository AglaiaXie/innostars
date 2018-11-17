<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvestor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investor_profiles', function (Blueprint $table) {
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
            $table->string('education')->nullable();
            //Document
            $table->integer('photo_file_id')->unsigned()->nullable();
            $table->foreign('photo_file_id')->references('id')->on('files')->onDelete('set null');
            $table->integer('resume_file_id')->unsigned()->nullable();
            $table->foreign('resume_file_id')->references('id')->on('files')->onDelete('set null');
        });

        Schema::create('investor_industries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('industry_id')->unsigned();
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('cascade');;
            $table->integer('investor_profile_id')->unsigned();
            $table->foreign('investor_profile_id')->references('id')->on('investor_profiles')->onDelete('cascade');;
        });

        Schema::create('investor_sub_industries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('investor_profile_id')->unsigned();
            $table->foreign('investor_profile_id')->references('id')->on('investor_profiles')->onDelete('cascade');;
            $table->integer('sub_industry_id')->unsigned();
            $table->foreign('sub_industry_id')->references('id')->on('sub_industries')->onDelete('cascade');
        });

        Schema::create('investor_competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('investor_profile_id')->unsigned();
            $table->foreign('investor_profile_id')->references('id')->on('investor_profiles')->onDelete('cascade');;
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
