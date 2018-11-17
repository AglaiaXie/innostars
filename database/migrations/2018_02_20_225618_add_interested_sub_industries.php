<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInterestedSubIndustries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interested_sub_industries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('judge_profile_id')->unsigned();
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles')->onDelete('cascade');
            $table->integer('sub_industry_id')->unsigned();
            $table->foreign('sub_industry_id')->references('id')->on('sub_industries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interested_sub_industries');
    }
}
