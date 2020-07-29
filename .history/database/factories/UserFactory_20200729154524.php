<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */



$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->text(rand(32, 10)),
        'user_name' => $faker->unique()->safeEmail,
        'email' => $faker->unique()->safeEmail,
        'password' => '123456',
        'api_token' => str_random(60)];
    ];
});
