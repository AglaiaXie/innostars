<?php

use App\Models\Competition;
use App\Models\Industry;
use Illuminate\Database\Migrations\Migration;

class AdjustSemifinal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var Competition $competitionA */
        $competitionA = Competition::find(7);
        $competitionA->update(['city' => Industry::NAME_IT, 'date' => null]);
        /** @var Competition $competitionB */
        $competitionB = Competition::find(16);
        $competitionA->all_judges()->saveMany($competitionB->all_judges()->get());
        $competitionB->delete();
        $unique_judges = $competitionA->all_judges()->get()->unique('judge_profile_id')->pluck('id');
        $competitionA->all_judges()->whereNotIn('id', $unique_judges)->delete();

        $competitionA = Competition::find(8);
        $competitionA->update(['city' => Industry::NAME_HEALTHCARE, 'date' => null]);
        $competitionB = Competition::find(12);
        $competitionA->all_judges()->saveMany($competitionB->all_judges()->get());
        $competitionB->delete();
        $competitionB = Competition::find(13);
        $competitionA->all_judges()->saveMany($competitionB->all_judges()->get());
        $competitionB->delete();
        $competitionB = Competition::find(14);
        $competitionA->all_judges()->saveMany($competitionB->all_judges()->get());
        $competitionB->delete();
        $unique_judges = $competitionA->all_judges()->get()->unique('judge_profile_id')->pluck('id');
        $competitionA->all_judges()->whereNotIn('id', $unique_judges)->delete();

        $competitionA = Competition::find(9);
        $competitionA->update(['city' => Industry::NAME_MANUFACTURING, 'date' => null]);

        $competitionA = Competition::find(10);
        $competitionA->update(['city' => Industry::NAME_ENERGY, 'date' => null]);
        $competitionB = Competition::find(11);
        $competitionB->delete();
        $unique_judges = $competitionA->all_judges()->get()->unique('judge_profile_id')->pluck('id');
        $competitionA->all_judges()->whereNotIn('id', $unique_judges)->delete();

        $competitionA = Competition::find(15);
        $competitionA->update(['city' => Industry::NAME_AI, 'date' => null]);

        Industry::create([
            'name' => Industry::NAME_MATERIALS,
        ]);

        Industry::where('name', '=', 'Renewable Energy and New Materials')->update(['name' => Industry::NAME_ENERGY]);

        $competitionA = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => Industry::NAME_MATERIALS,
        ]);

        $competitionA->industries()->save(Industry::where('name', '=', Industry::NAME_MATERIALS)->first());

        $competitionA = Competition::create([
            'name' => Competition::NAME_SEMI_FINAL,
            'city' => Industry::NAME_ENV,
        ]);

        $competitionA->industries()->save(Industry::where('name', '=', Industry::NAME_ENV)->first());
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
