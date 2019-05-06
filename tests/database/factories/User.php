<?php

use Faker\Generator as Faker;

$factory->define(\Tumainimosha\Verifiable\Tests\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->email,
    ];
});
