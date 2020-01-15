<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use App\ModelAndRepository\Categories\Category;

$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->unique()->randomElement([
        "Book",
        "Cloth",
        "Shoes",
        "Grocery"
    ]);

    $file = UploadedFile::fake()->image('category.jpg',500,500);
    return [
        "name" => $name,
        "slug" => Str::slug($name),
        "image" => $file->store("categories",["disk" => "public"])
    ];
});
