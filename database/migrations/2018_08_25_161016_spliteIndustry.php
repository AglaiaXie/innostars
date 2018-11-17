<?php

use App\Models\Company;
use App\Models\Industry;
use App\Models\InvestorProfile;
use App\Models\JudgeProfile;
use App\Models\SubIndustry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;

class SpliteIndustry extends Migration
{
    protected $new_materials = [
        'Metal/Metal Alloy',
        'Carbon Materials/Carbon Fiber',
        'Composites',
        'Membrane Materials',
        'Polymer Materials',
        'Photoelectric Materials',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var Industry $industry */
        $industry = Industry::where('name', '=', ' New Materials')->first();

        SubIndustry::whereIn('name', $this->new_materials)->update(['industry_id' => $industry->getKey()]);

        Company::whereHas('sub_industry', function (Builder $builder) {
            $builder->whereIn('name', $this->new_materials);
        })->update(['industry_id' => $industry->getKey()]);

        JudgeProfile::whereHas('interested_sub_industries', function (Builder $builder) {
            $builder->whereIn('name', $this->new_materials);
        })->get()->each(function (JudgeProfile $judge) use ($industry) {
            $judge->interested_industries()->save($industry);
        });

        JudgeProfile::whereHas('judging_sub_industries', function (Builder $builder) {
            $builder->whereIn('name', $this->new_materials);
        })->get()->each(function (JudgeProfile $judge) use ($industry) {
            $judge->judging_industries()->save($industry);
        });

        InvestorProfile::whereHas('interested_sub_industries', function (Builder $builder) {
            $builder->whereIn('name', $this->new_materials);
        })->get()->each(function (JudgeProfile $judge) use ($industry) {
            $judge->interested_industries()->save($industry);
        });

        Industry::where('name', '=', ' New Materials')->update(['name' => 'New Materials']);
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
