<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Faker\Generator as Faker;


$factory->define(User::class, function (Faker $faker) {

    $City = DB::table('cities')->select("id")->where("country_id",1)->get();
    $CityID = $faker->randomElement($City)->id;
    $District = DB::table('districts')->select("id")->where("country_id",1)->where("city_id",$CityID)->get();

    return [
        "name" => $faker->firstName,
        "surname" => $faker->lastName,
        "email" => $faker->unique()->safeEmail,
        "email_verified_at" => now(),
        "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
        "address" => $faker->address,
        "phone" => $faker->phoneNumber,
        "district_id" => $faker->randomElement($District)->id,
        "city_id" => $CityID,
        "country_id" => 1,
        "remember_token" => Str::random(10)
    ];
});

$factory->define(Product::class, function (Faker $faker) {

    $pizzaNo = $faker->unique()->numberBetween(1000,9999);
    return  [
        "name" => "Pizza-".$pizzaNo,
        "description" => "Pizza-".$pizzaNo." is ". $faker->text,
        "image" => $faker->numberBetween(1,8).".jpg",
        "price" => $faker->randomFloat(2,15,45)
    ];
});



