<?php

use App\Models\Competition;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubCompetitionModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competition_id')->unsigned();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->string('city');
            $table->date('start')->nullable();
            $table->date('end')->nullable();
        });

        /** @var Competition $competition */
        $competition = Competition::find(9);
        $competition->sub_competitions()->create([
            'city' => 'Nanjing',
            'start' => '2018-9-17',
            'end' => null,
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Beijing',
            'start' => '2018-9-18',
            'end' => '2018-9-19',
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Shenyang',
            'start' => '2018-9-20',
            'end' => '2018-9-21',
        ]);

        $competition = Competition::find(15);
        $competition->sub_competitions()->create([
            'city' => 'Shanghai',
            'start' => '2018-9-25',
            'end' => null,
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Nanjing',
            'start' => '2018-9-18',
            'end' => null,
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Qingdao',
            'start' => '2018-9-27',
            'end' => '2018-9-28',
        ]);

        $competition = Competition::find(7);
        $competition->sub_competitions()->create([
            'city' => 'Beijing',
            'start' => '2018-10-8',
            'end' => null,
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Luoyang',
            'start' => '2018-10-9',
            'end' => '2018-10-10',
        ]);
        $competition->sub_competitions()->create([
            'city' => 'TBD',
            'start' => null,
            'end' => null,
        ]);

        $competition = Competition::find(10);
        $competition->sub_competitions()->create([
            'city' => 'Beijing',
            'start' => '2018-11-3',
            'end' => '2018-11-5',
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Suzhou',
            'start' => '2018-11-6',
            'end' => '2018-11-7',
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Nanjing',
            'start' => '2018-11-8',
            'end' => null,
        ]);

        $competition = Competition::find(20);
        $competition->sub_competitions()->create([
            'city' => 'Beijing',
            'start' => '2018-11-3',
            'end' => null,
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Xi\'an',
            'start' => '2018-11-4',
            'end' => '2018-11-5',
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Suzhou',
            'start' => null,
            'end' => null,
        ]);

        $competition = Competition::find(21);
        $competition->sub_competitions()->create([
            'city' => 'Nanjing',
            'start' => null,
            'end' => null,
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Suzhou',
            'start' => null,
            'end' => null,
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Nanjing',
            'start' => null,
            'end' => null,
        ]);

        $competition = Competition::find(8);
        $competition->sub_competitions()->create([
            'city' => 'Suzhou',
            'start' => '2018-11-4',
            'end' => '2018-11-5',
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Najing',
            'start' => null,
            'end' => null,
        ]);
        $competition->sub_competitions()->create([
            'city' => 'Changzhou',
            'start' => null,
            'end' => null,
        ]);

        Schema::create('judging_sub_competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('judge_profile_id')->unsigned();
            $table->foreign('judge_profile_id')->references('id')->on('judge_profiles')->onDelete('cascade');
            $table->integer('sub_competition_id')->unsigned();
            $table->foreign('sub_competition_id')->references('id')->on('sub_competitions')->onDelete('cascade');
        });

        Schema::create('investor_sub_competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('investor_profile_id')->unsigned();
            $table->foreign('investor_profile_id')->references('id')->on('investor_profiles')->onDelete('cascade');
            $table->integer('sub_competition_id')->unsigned();
            $table->foreign('sub_competition_id')->references('id')->on('sub_competitions')->onDelete('cascade');
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
