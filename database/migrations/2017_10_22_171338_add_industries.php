<?php

use App\Models\Industry;
use Illuminate\Database\Migrations\Migration;

class AddIndustries extends Migration
{
    protected $industries = [
        [
            'name' => Industry::NAME_HEALTHCARE,
        ],
        [
            'name' => Industry::NAME_MANUFACTURING,
        ],
        [
            'name' => Industry::NAME_ENV,
        ],
        [
            'name' => Industry::NAME_IT,
        ],
        [
            'name' => Industry::NAME_AI,
        ],
        [
            'name' => Industry::NAME_ENERGY,
        ],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->industries as $industry) {
            Industry::create($industry);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Industry::truncate();
    }
}
