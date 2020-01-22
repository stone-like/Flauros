<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\ModelAndRepository\Users\User;
use App\ModelAndRepository\Addresses\Address;
use App\ModelAndRepository\Countries\Country;
use App\ModelAndRepository\Prefectures\Prefecture;

$factory->define(Address::class, function (Faker $faker) {
    $country = Country::all()->random();
    $prefecture = Prefecture::where("id",$country->id)->first();
    return [
        "zip"=>$faker->postcode,
        "address1"=>$faker->streetAddress,
        "country_id"=>$country->id,
        "prefecture_id"=>$prefecture->id,
        "user_id" => function(){
            return User::all()->random();
        }
    ];
});
