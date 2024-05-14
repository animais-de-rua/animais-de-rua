<?php

use App\Helpers\EnumHelper;
use Backpack\Base\app\Models\BackpackUser as User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Use Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(User::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    $fake_images = [
        0 => 'img/animals.jpg',
        1 => 'img/association.jpg',
        2 => 'img/association01.jpg',
        3 => 'img/association02.jpg',
        4 => 'img/ced.jpg',
        5 => 'img/ced01.jpg',
        6 => 'img/ced02.jpg',
        7 => 'img/friend.jpg',
        8 => 'img/help.jpg',
        9 => 'img/help01.jpg',
        10 => 'img/help02.jpg',
        11 => 'img/home.jpg',
    ];

    return [
        'name' => $faker->firstName . ' ' . $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'petsitting_role' => ucfirst($faker->randomElement(EnumHelper::get('user.petsitting.roles'))),
        'petsitting_description' => $faker->text(),
        'petsitting_image' => $faker->randomElement($fake_images),
        'status' => $faker->randomElement(array_keys(EnumHelper::get('user.status'))),
        'password' => '$2y$10$ZoyR5TtV3.V.QCMvYVRL2.LFaO13PGl50Bfxxz629zY.fY8BzoIfe',
        'remember_token' => str_random(10),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
