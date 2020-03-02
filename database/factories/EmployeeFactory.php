<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
    	'first_name' => $faker->firstNameFemale,
    	'middle_name' => $faker->firstNameMale,
        'last_name' => $faker->lastName,
		'position' => $faker->jobTitle,
		'email' => $faker->email,
		'work_phone' => $faker->e164PhoneNumber,
		'mobile_phone' => $faker->phoneNumber,
	];
});
