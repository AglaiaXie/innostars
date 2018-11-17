<?php

use App\Models\Competition;
use Illuminate\Database\Migrations\Migration;

class UpdateCompetitionAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Competition::find(9)->update([
            'city' => 'Shenyang',
            'date' => '2018-09-20'
        ]);

        Competition::find(15)->update([
            'city' => 'Qingdao',
            'date' => '2018-09-27'
        ]);

        Competition::find(10)->update([
            'city' => 'Beijing',
        ]);

        Competition::find(7)->update([
            'city' => 'Luoyang',
            'date' => '2018-10-09'
        ]);

        Competition::find(21)->update([
            'city' => 'Tangshan',
        ]);

        Competition::find(20)->update([
            'city' => 'Xi\'an',
            'date' => '2018-11-04'
        ]);

        Competition::find(8)->update([
            'city' => 'Suzhou',
            'date' => '2018-11-06'
        ]);

        Competition::create([
            'name' => Competition::NAME_FINAL,
            'city' => 'Suzhou',
            'date' => '2018-11-07',
        ]);
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
