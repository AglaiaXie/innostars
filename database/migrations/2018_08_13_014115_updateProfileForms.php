<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProfileForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('been_to_china')->nullable()->after('refer');
        });

        Schema::table('partner_profiles', function (Blueprint $table) {
            //Basic
            $table->string('refer')->nullable()->after('reason');
            $table->string('contact_person')->nullable()->after('reason');
            $table->string('email')->nullable()->afeter('reason');
            //Addition
            $table->integer('real_logo_file_id')->unsigned()->nullable()->after('logo_file_id');
            $table->foreign('real_logo_file_id')->references('id')->on('files')->onDelete('set null');
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
