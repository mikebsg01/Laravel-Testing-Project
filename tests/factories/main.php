<?php

$factory('App\User', [
	'first_name' => $faker->firstName,
	'last_name' => $faker->lastName,
	'email'	=> $faker->email,
	'password' => $faker->password
]);
