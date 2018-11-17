<?php

use App\Models\Area;
use App\Models\Competition;
use App\Models\Industry;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class AddCompetitions extends Migration
{
    protected $preliminary = [
        'name' => 'Preliminary Stage',
    ];

    protected $semiFinal = [
        'name' => 'Semi-finals',
    ];

    protected $final = [
        'name' => 'Final',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $chicago = Competition::create([
            'name' => Competition::NAME_PRELIMINARY_STAGE,
            'city' => 'Chicago',
            'date' => Carbon::createFromDate(2018, 3, 12),
        ]);

        $chicago->industries()->saveMany(Industry::whereIn('name', [Industry::NAME_ENV, Industry::NAME_ENERGY])->get());

        $atlanta = Competition::create([
            'name' => Competition::NAME_PRELIMINARY_STAGE,
            'city' => 'Atlanta',
            'date' => Carbon::createFromDate(2018, 5, 11),
        ]);

        $atlanta->industries()->saveMany(Industry::whereIn('name', [Industry::NAME_MANUFACTURING, Industry::NAME_ENV, Industry::NAME_IT])->get());

        $houston = Competition::create([
            'name' => Competition::NAME_PRELIMINARY_STAGE,
            'city' => 'Houston',
            'date' => Carbon::createFromDate(2018, 5, 15),
        ]);

        $houston->industries()->saveMany(Industry::all());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Competition::delete();
    }
}
