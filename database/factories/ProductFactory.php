<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\User;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'product_name' => $faker->firstNameFemale,
        'price' => $faker->randomNumber(6),
        'imageurl' => $faker->imageUrl(),
        'stock' => $faker->randomNumber(2),
        'created_by' => factory(User::class)
    ];
});
