<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerUserTest extends TestCase
{

    public function testCreate()
    {
        $City = DB::table('cities')->select("id")->where("country_id",1)->get();
        $CityID = $this->faker->randomElement($City)->id;
        $District = DB::table('districts')->select("id")->where("country_id",1)->where("city_id",$CityID)->get();


        $data = [
            "name" => $this->faker->firstName,
            "surname" => $this->faker->lastName,
            "email" => "test@pizzapp.test",
            "email_verified_at" => now(),
            "password" => 'password',
            "address" => $this->faker->address,
            "phone" => $this->faker->phoneNumber,
            "district_id" => $this->faker->randomElement($District)->id,
            "city_id" => $CityID,
            "country_id" => 1,
            "remember_token" => Str::random(10)
        ];

        $response = $this->post("/register", $data);
        $response->assertStatus(201);

        $user = json_decode($response->getContent());

        return $user;

    }

    /**
     * @depends testCreate
     */

    public function testLogin($user)
    {

        $data = [
            "username" => $user->email,
            "password" => 'password'
        ];


        $datas = $this->post("/login", $data);
        $datas->assertStatus(200);
        $user->token = json_decode($datas->getContent())->access_token;

        return $user;

    }

    public function testAdminLogin()
    {

        $data = [
            "username" => "admin@pizzapp.test",
            "password" => 'password'
        ];


        $datas = $this->post("/login", $data);
        $datas->assertStatus(200);
        $token = json_decode($datas->getContent())->access_token;


        return $token;

    }

    /**
     * @depends testLogin
     */

    public function testUserList($user)
    {
        /**
         * Status 500 bekleniyor. Admin olmayan, kullanıcı listesine erişemez.
         */
        $this->get("/admin/user")
        ->assertStatus(500);

        /**
         * Status 405 bekleniyor. Normal kullanıcılar için listeleme sayfası bulunmuyor
         */
        $this->get("/user", ["Authorization" => "Bearer ".$user->token])
        ->assertStatus(405);

        /**
         * Status 403 bekleniyor. Admin olmayan, kullanıcı listesine erişemez.
         */
        $this->get("/admin/user", ["Authorization" => "Bearer ".$user->token])
        ->assertStatus(403);



    }

    /**
     * @depends testLogin
     */

    public function testUpdate($user)
    {
        $City = DB::table('cities')->select("id")->where("country_id",1)->get();
        $CityID = $this->faker->randomElement($City)->id;
        $District = DB::table('districts')->select("id")->where("country_id",1)->where("city_id",$CityID)->get();

        $data = [
            "name" => $this->faker->firstName,
            "surname" => $this->faker->lastName,
            "address" => $this->faker->address,
            "phone" => $this->faker->phoneNumber,
            "district_id" => $this->faker->randomElement($District)->id,
            "city_id" => $CityID,
            "country_id" => 1,
            "remember_token" => Str::random(10)
        ];

        /**
         * Status 500 bekleniyor. Login olunmadan kullanıcı datası değiştirilemez.
         */
        $this->put("/user/".$user->id, $data)
        ->assertStatus(500);

        /**
         * Status 200 bekleniyor. Login olunursa kullanıcı datası değiştirilebilir.
         */
        $datas = $this->put("/user/".$user->id, $data,["Authorization" => "Bearer ".$user->token]);
        $datas->assertStatus(200);


    }

    /**
     * @depends testLogin
     */

    public function testDelete($user)
    {
        /**
         * Status 405 bekleniyor. Admin olmayan üyelik bilgisini silemez.
         */
        $this->delete("/user/".$user->id, [],["Authorization" => "Bearer ".$user->token])
        ->assertStatus(405);


    }

    /**
     * @depends testLogin
     * @depends testAdminLogin
     */

    public function testAdmin($user,$adminToken)
    {

        /**
         * Status 200 bekleniyor. Admin.
         */
        $this->get("/admin/user", ["Authorization" => "Bearer ".$adminToken])
        ->assertStatus(200);


        $City = DB::table('cities')->select("id")->where("country_id",1)->get();
        $CityID = $this->faker->randomElement($City)->id;
        $District = DB::table('districts')->select("id")->where("country_id",1)->where("city_id",$CityID)->get();

        $data = [
            "name" => $this->faker->firstName,
            "surname" => $this->faker->lastName,
            "address" => $this->faker->address,
            "phone" => $this->faker->phoneNumber,
            "district_id" => $this->faker->randomElement($District)->id,
            "city_id" => $CityID,
            "country_id" => 1,
            "remember_token" => Str::random(10)
        ];

        /**
         * Status 200 bekleniyor. Admin.
         */
        $this->put("/admin/user/".$user->id, $data,["Authorization" => "Bearer ".$adminToken])
        ->assertStatus(200);
    }
}
