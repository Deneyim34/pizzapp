<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
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

        $id = Product::orderBy('id', 'desc')->first()->id;

        $data = new \stdClass;
        $data->params = [];
        $data->params["products"] = [[
            "id" => $id,
            "product_id" => $id,
            "price" => 20.0,
            "quantity" => 5,
            "total_price" => 100.0,
            "size_id" => 1
            ]];

        /**
         * Status 201 bekleniyor.
         */

        $response = $this->post("/order", (array)$data,["Authorization" => "Bearer ".$token]);
        $response->assertStatus(201);

    }


    /**
     * @depends testLogin
     */

    public function testList($token)
    {

        /**
         * Status 500 bekleniyor. Admin olmayan, kullanıcı listesine erişemez.
         */
        $this->get("/admin/order")
        ->assertStatus(500);

        /**
         * Status 200 bekleniyor.
         */
        $this->get("/order", ["Authorization" => "Bearer ".$token])
        ->assertStatus(200);

        /**
         * Status 403 bekleniyor. Admin olmayan, kullanıcı listesine erişemez.
         */
        $this->get("/admin/order", ["Authorization" => "Bearer ".$token])
        ->assertStatus(403);

    }

    /**
     * @depends testLogin
     */

    public function testUpdate($token)
    {

        $data =  [
            "status_id" => "4"
        ];

        $id = Order::orderBy('id', 'desc')->first()->id;

        /**
         * Status 404 bekleniyor. Normal kullanıcılar için sipariş edit sayfası bulunmuyor.
         */
        $this->put("/order/".$id, $data,["Authorization" => "Bearer ".$token])
        ->assertStatus(404);

        /**
         * Status 403 bekleniyor. Admin olmayan sipariş bilgisi değiştiremez.
         */
        $this->put("/admin/order/".$id,  $data,["Authorization" => "Bearer ".$token])
        ->assertStatus(403);



    }

    /**
     * @depends testLogin
     */

    public function testDelete($token)
    {

        $id = Order::orderBy('id', 'desc')->first()->id;

        /**
         * Status 404 bekleniyor. Normal kullanıcılar için sipariş silme sayfası bulunmuyor.
         */
        $this->delete("/order/".$id, [],["Authorization" => "Bearer ".$token])
        ->assertStatus(404);

        /**
         * Status 403 bekleniyor. Admin olmayan sipariş silemez.
         */
        $this->delete("/admin/order/".$id, [],["Authorization" => "Bearer ".$token])
        ->assertStatus(403);


    }

    /**
     * @depends testAdminLogin
     */

    public function testAdmin($adminToken)
    {
        $id = Product::orderBy('id', 'desc')->first()->id;

        $data = new \stdClass;
        $data->params = [];
        $data->params["products"] = [[
            "id" => $id,
            "product_id" => $id,
            "price" => 20.0,
            "quantity" => 5,
            "total_price" => 100.0,
            "size_id" => 1
            ]];

        /**
         * Status 201 bekleniyor. Admin.
         */
        $response = $this->post("/admin/order", (array)$data,["Authorization" => "Bearer ".$adminToken]);
        $response->assertStatus(201);

        $id = json_decode($response->getContent())->id;


        /**
         * Status 200 bekleniyor. Admin.
         */
        $this->get("/admin/order", ["Authorization" => "Bearer ".$adminToken])
        ->assertStatus(200);

        $data =  [
            "status_id" => "4"
        ];

        /**
         * Status 200 bekleniyor. Admin.
         */
        $this->put("/admin/order/".$id, $data,["Authorization" => "Bearer ".$adminToken])
        ->assertStatus(200);


        /**
         * Status 204 bekleniyor. Admin.
         */
        $this->delete("/admin/order/".$id, [],["Authorization" => "Bearer ".$adminToken])
        ->assertStatus(204);
    }


    /**
     * @depends testAdminLogin
     */

    public function testFinish($adminToken)
    {
        $id = User::where('email',"=", 'test@pizzapp.test')->first()->id;
        /**
         * Status 204 bekleniyor. Admin.
         */
        $this->delete("/admin/user/".$id, [],["Authorization" => "Bearer ".$adminToken])
        ->assertStatus(204);
    }

}
