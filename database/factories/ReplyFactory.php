<?php

use Faker\Generator as Faker;
use App\Models\Reply;

$factory->define(Reply::class, function (Faker $faker) {

    $time = $faker->dateTimeThisMonth();

    return [
        'content' => $faker->sentence(),
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
