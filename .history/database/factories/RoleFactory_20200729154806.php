<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */



$factory->define(App\Role::class, function (Faker\Generator $faker) {
    return [
        'description' => 'Professional',
        'role' => '1',
        'state' => 'ACTIVE',
    ];
});
