<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Course;
use App\Models\Topic;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Topic::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(4),
        'content' => $faker->text(),
        'user_id' => User::where('role', User::STUDENT)->get()->random()->id,
        'course_id' => Course::all()->random()->id,
        'created_at' => now()
    ];
});
