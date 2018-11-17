<?php

use App\Models\Competition;
use App\Models\Industry;
use Illuminate\Database\Migrations\Migration;

class AddFinal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var Competition $competition */
        $competition = Competition::where('name', '=', Competition::NAME_FINAL)->first();

        $competition->industries()->saveMany(Industry::all());

        $competition = Competition::create([
            'name' => Competition::NAME_FINAL,
            'city' => 'Special Event - Guangzhou',
            'date' => '2018-12-15',
        ]);

        $competition->industries()->saveMany(Industry::all());

        $competition = Competition::create([
            'name' => Competition::NAME_FINAL,
            'city' => 'Special Event - Suzhou',
            'date' => '2018-11-07',
        ]);

        $competition->industries()->saveMany(Industry::all());
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
