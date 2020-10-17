<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerProductTest extends TestCase
{

    public function testLogin()
    {

        $data = [
            "username" => "test@pizzapp.test",
            "password" => 'password'
        ];


        $datas = $this->post("/login", $data);
        $datas->assertStatus(200);
        $token = json_decode($datas->getContent())->access_token;

        return $token;

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

    public function testCreate($token)
    {
        $pizzaNo = $this->faker->unique()->numberBetween(1000,9999);

        $data =  [
            "name" => "Pizza-".$pizzaNo,
            "description" => "Pizza-".$pizzaNo." is ". $this->faker->text,
            "image" => $this->faker->numberBetween(1,8).".jpg",
            "price" => $this->faker->randomFloat(2,15,45),
            "active" => 0
        ];

        /**
         * Status 405 bekleniyor. Normal kullanıcılar ürün ekleyemez.
         */
        $response = $this->post("/product", $data,["Authorization" => "Bearer ".$token]);
        $response->assertStatus(405);
    }


    /**
     * @depends testLogin
     */

    public function testList($token)
    {

        /**
         * Status 500 bekleniyor. Admin olmayan, kullanıcı listesine erişemez.
         */
        $this->get("/admin/product")
        ->assertStatus(500);

        /**
         * Status 200 bekleniyor.
         */
        $this->get("/product", ["Authorization" => "Bearer ".$token])
        ->assertStatus(200);

        /**
         * Status 403 bekleniyor. Admin olmayan, kullanıcı listesine erişemez.
         */
        $this->get("/admin/product", ["Authorization" => "Bearer ".$token])
        ->assertStatus(403);



    }

    /**
     * @depends testLogin
     */

    public function testUpdate($token)
    {
        $pizzaNo = $this->faker->unique()->numberBetween(1000,9999);

        $data =  [
            "name" => "Pizza-".$pizzaNo,
            "description" => "Pizza-".$pizzaNo." is ". $this->faker->text,
            "image" => $this->faker->numberBetween(1,8).".jpg",
            "price" => $this->faker->randomFloat(2,15,45)
        ];

        $id = Product::orderBy('id', 'desc')->first()->id;

        /**
         * Status 405 bekleniyor. Normal kullanıcılar için ürün edit sayfası bulunmuyor.
         */
        $this->put("/product/".$id, $data,["Authorization" => "Bearer ".$token])
        ->assertStatus(405);

        /**
         * Status 403 bekleniyor. Admin olmayan ürün bilgisi değiştiremez.
         */
        $this->put("/admin/product/".$id, $data,["Authorization" => "Bearer ".$token])
        ->assertStatus(403);




    }

    /**
     * @depends testLogin
     */

    public function testDelete($token)
    {

        $id = Product::orderBy('id', 'desc')->first()->id;

        /**
         * Status 405 bekleniyor. Normal kullanıcılar için ürün silme sayfası bulunmuyor.
         */
        $this->delete("/product/".$id, [],["Authorization" => "Bearer ".$token])
        ->assertStatus(405);

        /**
         * Status 403 bekleniyor. Admin olmayan ürün silemez.
         */
        $this->delete("/admin/product/".$id, [],["Authorization" => "Bearer ".$token])
        ->assertStatus(403);
    }

    /**
     * @depends testAdminLogin
     */

    public function testAdmin($adminToken)
    {
        $pizzaNo = $this->faker->unique()->numberBetween(1000,9999);

        $data =  [
            "name" => "Pizza-".$pizzaNo,
            "description" => "Pizza-".$pizzaNo." is ". $this->faker->text,
            "price" => $this->faker->randomFloat(2,15,45),
            "active" => 0
        ];

        /**
         * Status 201 bekleniyor. Admin.
         */
        $response = $this->post("/admin/product", $data,["Authorization" => "Bearer ".$adminToken]);
        $response->assertStatus(201);


        $id = json_decode($response->getContent())->id;


        /**
         * Status 200 bekleniyor. Admin.
         */
        $this->get("/admin/product", ["Authorization" => "Bearer ".$adminToken])
        ->assertStatus(200);

        $pizzaNo = $this->faker->unique()->numberBetween(1000,9999);

        $data =  [
            "name" => "Pizza-".$pizzaNo,
            "description" => "Pizza-".$pizzaNo." is ". $this->faker->text,
            "price" => $this->faker->randomFloat(2,15,45)
        ];

        /**
         * Status 200 bekleniyor. Admin.
         */
        $this->put("/admin/product/".$id, $data,["Authorization" => "Bearer ".$adminToken])
        ->assertStatus(200);


        /**
         * Status 204 bekleniyor. Admin.
         */
        $this->delete("/admin/product/".$id, [],["Authorization" => "Bearer ".$adminToken])
        ->assertStatus(204);
    }

}
