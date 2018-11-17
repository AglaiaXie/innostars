<?php

use App\Models\Competition;
use App\Models\SubCompetition;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompetitionTranslation extends Migration
{
    protected $competitionNames = [
        'Online Stage' => '海选',
        'Preliminary Stage' => '初赛',
        'Semi-finals' => '半决赛',
        'Grand Final' => '决赛',
    ];

    protected $subCompetitionCities = [
        'Nanjing' => '南京',
        'Beijing' => '北京',
        'Shenyang' => '沈阳',
        'Shanghai' => '上海',
        'Qingdao' => '青岛',
        'Luoyang' => '洛阳',
        'Suzhou' => '苏州',
        'Xi\'an' => '西安',
        'Tianjin' => '天津',
        'Taiyuan' => '太原',
        'Zhuhai' => '珠海',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->string('name_cn')->after('name')->nullable();
            $table->string('city_cn')->after('city')->nullable();
        });

        Schema::table('sub_competitions', function (Blueprint $table) {
            $table->string('city_cn')->after('city')->nullable();
        });

        foreach ($this->competitionNames as $name => $translation) {
            Competition::where('name', '=', $name)->update(['name_cn' => $translation]);
        }

        foreach ($this->subCompetitionCities as $name => $translation) {
            SubCompetition::where('city', '=', $name)->update(['city_cn' => $translation]);
        }
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
