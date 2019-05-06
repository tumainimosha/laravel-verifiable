<?php

use Faker\Generator as Faker;

$factory->define(\Tumainimosha\Verifiable\Tests\Models\Order::class, function (Faker $faker) {
    return [
        'reference' => $faker->unique()->lexify('???-???-???'),
        'amount' => $faker->numberBetween(1000, 10000),
    ];
});
