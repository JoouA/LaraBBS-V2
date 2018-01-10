<?php

use Faker\Generator as Faker;
use App\Models\Topic;

$factory->define(Topic::class, function (Faker $faker) {

    $faker = \Faker\Factory::create('zh_CN');

    $sentence = $faker->sentence();

    //随机获取一个月内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'title' => $sentence,
        'body' => $faker->text(),
        'excerpt' => $sentence,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
