<?php

use App\Models\Industry;
use Illuminate\Database\Migrations\Migration;

class AddSubIndustryEntries extends Migration
{
    protected $entries = [
        Industry::NAME_HEALTHCARE => [
            ['name' => 'Next Generation Sequencing'],
            ['name' => 'Pharmaceuticals'],
            ['name' => 'Medical Device'],
            ['name' => 'Digital Health'],
            ['name' => 'Genomics'],
            ['name' => 'Cancer Diagnosis &amp; Treatment'],
            ['name' => 'Precision Medicine'],
            ['name' => 'Cell Therapy'],
            ['name' => 'Tumor Drug Development'],
            ['name' => 'New Vaccines'],
            ['name' => 'PCR Technology and Diagnostic Reagents'],
            ['name' => 'Biochip'],
        ],
        Industry::NAME_MANUFACTURING => [
            ['name' => 'Automatic Transportation'],
            ['name' => '3D Printing'],
            ['name' => 'Next Generation Computer-Aided Design Software'],
            ['name' => 'Computer-Aided Design Software'],
            ['name' => 'Robotics'],
            ['name' => 'Autonomous Vehicle'],
            ['name' => 'Additive Manufacturing'],
        ],
        Industry::NAME_ENV => [
            ['name' => 'Energy Recycling'],
            ['name' => 'Eco-protection'],
            ['name' => 'Water &amp; Wastewater Treatment'],
            ['name' => 'Recycling &amp; Waste'],
            ['name' => 'Air Emissions Control'],
            ['name' => 'Soil &amp; Water Pollution Treatment'],
        ],
        Industry::NAME_IT => [
            ['name' => 'Smart Cities'],
            ['name' => 'Industrial IoT'],
            ['name' => 'Connected Health'],
            ['name' => 'Smart Homes'],
            ['name' => 'Connected Cars'],
            ['name' => 'Wearables'],
            ['name' => 'Smart Utilities'],
            ['name' => 'Communications Equipment'],
            ['name' => 'Internet Software &amp; Services'],
            ['name' => 'Technology Hardware, Storage &amp; Peripherals'],
        ],
        Industry::NAME_AI => [
            ['name' => 'Digital Assistants'],
            ['name' => 'Neurocomputers'],
            ['name' => 'Embedded Systems'],
            ['name' => 'Expert Systems'],
            ['name' => 'AR/VR Hardware'],
            ['name' => 'AR/VR Content'],
            ['name' => 'AR/VR Data'],
            ['name' => 'AR/VR Commerce'],
            ['name' => 'Enterprise AR/VR'],
        ],
        Industry::NAME_ENERGY => [
            ['name' => 'Energy Storage Technology'],
            ['name' => 'Unconventional Oil &amp; Gas Exploration'],
            ['name' => 'Metal/Metal Alloy'],
            ['name' => 'Carbon Materials/Carbon Fiber'],
            ['name' => 'Composites'],
            ['name' => 'Energy Efficiency Improvement'],
            ['name' => 'Smart Grid &amp; Energy Storage'],
            ['name' => 'Solar Energy'],
            ['name' => 'Biomass Energy'],
            ['name' => 'Hydro &amp; Wind Energy'],
            ['name' => 'Membrane Materials'],
            ['name' => 'Polymer Materials'],
            ['name' => 'Photoelectric Materials'],
        ]
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->entries as $key => $entry) {
            /** @var Industry $industry */
            $industry = Industry::where('name', $key)->firstOrFail();
            $industry->sub_industries()->createMany($entry);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('sub_industries')->delete();
    }
}
