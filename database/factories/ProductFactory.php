<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use App\ModelAndRepository\Products\Product;

$factory->define(Product::class, function (Faker $faker) {
    $name = $faker->unique()->sentence;
    //カテゴリーは別にユーザーが追加する物じゃないしここで適当に毎回入れちゃっていいかな
   
    return [
        "name" => $name,
        "slug" => Str::slug($name),
        "description" => $faker->paragraph,
        "quantity" => 5,
        "price" => 10.00,
        "status" => "5 items left"
    ];
});
