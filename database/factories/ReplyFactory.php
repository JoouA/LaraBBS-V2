<?php

use Faker\Generator as Faker;
use App\Models\Reply;

$factory->define(Reply::class, function (Faker $faker) {

    $faker = \Faker\Factory::create('zh-CN');

    $time = $faker->dateTimeThisMonth();

    return [
        'content' => $faker->sentence(),
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
