<?php

use App\Models\Company;
use App\Models\JudgeProfile;
use App\Models\Score;
use App\Models\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name'     => $faker->firstName,
        'last_name'      => $faker->lastName,
        'email'          => $faker->unique()->safeEmail,
        'password'       => bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name'        => $faker->company,
        'description' => $faker->sentence(10),
        'size'        => $faker->numberBetween(10, 1000),
        'established' => $faker->year(),
        'website'     => $faker->url,
        'address'     => $faker->address,
        'address2'    => '',
        'city'        => $faker->city,
        'zip_code'    => $faker->postcode,
        'state'       => 'Texas',
        'country'     => 'US',
        'approval'    => false,
    ];
});

$factory->define(JudgeProfile::class, function (Faker $faker) {
    return [
        'company_name' => $faker->company,
        'education'    => $faker->randomElement(['Bachelor', 'Master', 'PHD']),
        'phone'        => $faker->phoneNumber,
        'position'     => $faker->jobTitle,
        'refer'        => $faker->linuxProcessor,
        'experience'   => $faker->sentence(10),
    ];
});

$factory->define(Score::class, function (Faker $faker) {
    return [
        'score'   => $faker->numberBetween(1, 10),
        'comment' => $faker->paragraph(10),
    ];
});