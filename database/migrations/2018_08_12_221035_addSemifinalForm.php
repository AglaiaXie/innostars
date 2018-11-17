<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSemifinalForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semifinal_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->boolean('submit')->default(false);
            $table->timestamps();
            //Basic
            $table->string('leader')->nullable();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('nationality')->nullable();
            $table->string('education')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('graduating_school')->nullable();
            $table->string('major_research')->nullable();
            //Core Technology
            $table->string('patent_name')->nullable();
            $table->string('patent_country')->nullable();
            $table->string('patent_type')->nullable();
            $table->string('patent_number')->nullable();
            $table->string('patent_date')->nullable();
            $table->string('software_copyright')->nullable();
            $table->string('software_country')->nullable();
            $table->string('software_owner')->nullable();
            $table->string('software_number')->nullable();
            $table->string('software_date')->nullable();
            //Market Operation
            $table->text('market_analysis')->nullable();
            $table->text('competition_advantages')->nullable();
            $table->text('market_competition_analysis')->nullable();
            $table->text('business_model')->nullable();
            $table->text('business_development_plan')->nullable();
            $table->text('operating_risk_and_strategy')->nullable();
            //Financing Experience
            $table->string('current_financing_stage')->nullable();
            $table->string('equity_investor_investing_institution')->nullable();
            $table->string('equity_financing_amount')->nullable();
            $table->string('equity_percentage_of_equity')->nullable();
            $table->string('equity_date_of_investing')->nullable();
            $table->string('debt_investor_investing_institution')->nullable();
            $table->string('debt_financing_amount')->nullable();
            $table->string('debt_percentage_of_equity')->nullable();
            $table->string('debt_date_of_investing')->nullable();
            //Forecast Financial Date
            $table->string('business_revenue_2018')->nullable();
            $table->string('business_revenue_2019')->nullable();
            $table->string('business_revenue_2020')->nullable();
            $table->string('business_cost_2018')->nullable();
            $table->string('business_cost_2019')->nullable();
            $table->string('business_cost_2020')->nullable();
            $table->string('business_tax_2018')->nullable();
            $table->string('business_tax_2019')->nullable();
            $table->string('business_tax_2020')->nullable();
            $table->string('business_profit_2018')->nullable();
            $table->string('business_profit_2019')->nullable();
            $table->string('business_profit_2020')->nullable();
            $table->string('business_profit_margin_2018')->nullable();
            $table->string('business_profit_margin_2019')->nullable();
            $table->string('business_profit_margin_2020')->nullable();
            $table->string('net_profit_2018')->nullable();
            $table->string('net_profit_2019')->nullable();
            $table->string('net_profit_2020')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('semifinal_form');
    }
}
