<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\ModelAndRepository\Countries\Country;
use App\ModelAndRepository\Prefectures\Prefecture;


$factory->define(Prefecture::class, function (Faker $faker) {

    return [
        
        "name" => $faker->word
    ];
});
