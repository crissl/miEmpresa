<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */



$factory->define(App\Company::class, function (Faker\Generator $faker) {
    return [
        'identity' => $faker->text(rand(32, 10)),
        'nature' => $faker->text(rand(32, 10)),
        'trade_name' => $faker->text(rand(32, 10)),
        'email' => $faker->unique()->safeEmail,
        'comercial_activity' => $faker->text(rand(32, 10)),
        'phone' => $faker->text(rand(32, 10)),
        'cell_phone' => $faker->text(rand(32, 10)),
        'address' => $faker->text(rand(32, 10)),
        'state' => 'ACTIVE',
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
