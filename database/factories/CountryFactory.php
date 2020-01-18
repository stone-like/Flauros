<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use Faker\Generator as Faker;
use App\ModelAndRepository\Countries\Country;

$factory->define(Country::class, function (Faker $faker) {
    $name = $faker->unique()->randomElement([
       "Japan",
       "America",
       "French",
       "China"
    ]);
    return [
        "name" => $name
    ];
});
