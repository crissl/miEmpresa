<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */



$factory->define(App\Professional::class, function (Faker\Generator $faker) {
    return [
        'identity' => str_random(10),
        'first_name' => str_random(10),
        'last_name' => str_random(10),
        'email' => $faker->unique()->safeEmail,
        'nationality' => str_random(10),
        'civil_state' => str_random(10),
        'birthdate' => '2018-10-01',
        'gender' => str_random(10),
        'phone' => str_random(10),
        'address' => str_random(10),
        'state' => str_random(10),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
