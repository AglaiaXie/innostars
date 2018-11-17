<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubIndustries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_industries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('industry_id')->unsigned();
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('cascade');
            $table->string('name');
        });

        Schema::create('judging_sub_industries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('judge_profile_id')->unsigned();
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles')->onDelete('cascade');
            $table->integer('sub_industry_id')->unsigned();
            $table->foreign('sub_industry_id')->references('id')->on('sub_industries')->onDelete('cascade');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->integer('sub_industry_id')->unsigned()->nullable()->after('industry_id');
            $table->foreign('sub_industry_id')->references('id')->on('sub_industries')->onDelete('set null');
        });

        Schema::dropIfExists('company_industry');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign('companies_sub_industry_id_foreign');
            $table->dropColumn('sub_industry_id');
        });
        Schema::dropIfExists('judging_sub_industries');
        Schema::dropIfExists('sub_industries');
    }
}
