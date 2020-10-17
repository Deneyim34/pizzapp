<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Str;

class DefaultDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Ürün Boyut Datası Oluşturuluyor
         */
        $Sizes = [
            [
                "short_name" => "S",
                "long_name" => "Small"
            ],
            [
                "short_name" => "M",
                "long_name" => "Medium"
            ],
            [
                "short_name" => "L",
                "long_name" => "Large"
            ]];


        foreach($Sizes as $Size)
        {
            DB::table("product_sizes")->insert(
                $Size
            );
        }

        /**
         * Sipariş Durum Datası Oluşturuluyor
         */
        $Statuses = [
            [
                "name" => "Pending"
            ],
            [
                "name" => "Preparing"
            ],
            [
                "name" => "On The Way"
            ],
            [
                "name" => "Delivered"
            ],
            [
                "name" => "Canceled"
            ]
            ];


        foreach($Statuses as $Status)
        {
            DB::table("status")->insert(
                $Status
            );
        }


        $faker = Faker\Factory::create();

        /**
         * Öntanımlı "Admin" ve "Müşteri" Hesabı Oluşturuluyor
         */

        $Users = [
            [
                "name" => "Admin",
                "surname" => "Hesabı",
                "email" => "admin@pizzapp.test",
                "email_verified_at" => now(),
                "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //şifre : password
                "address" => $faker->address,
                "phone" => $faker->phoneNumber,
                "district_id" => 440,
                "city_id" => 34,
                "country_id" => 1,
                "remember_token" => Str::random(10),
                "role" => 2 //ADMIN
            ],
            [
                "name" => "Müşteri",
                "surname" => "Hesabı",
                "email" => "customer@pizzapp.test",
                "email_verified_at" => now(),
                "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //şifre : password
                "address" => $faker->address,
                "phone" => $faker->phoneNumber,
                "district_id" => 440,
                "city_id" => 34,
                "country_id" => 1,
                "remember_token" => Str::random(10),
                "role" => 1 //MÜŞTERİ
            ]
            ];

        foreach($Users as $User)
        {
            DB::table("users")->insert(
                $User
            );
        }


        /**
         * Müşteri Datası Oluşturuluyor
         */
        factory(App\Models\User::class,100)->create();
        /**
         * Ürün Datası Oluşturuluyor
         */
        factory(App\Models\Product::class,100)->create();

        /**
         * Sipariş Datası Oluşturuluyor
         */

        for($i = 0; $i < 100; $i++)
        {
            $datas = [];
            $total_price = 0;

            for($j = 0; $j < $faker->numberBetween(1,6); $j++)
            {
                $product_id = $faker->numberBetween(1,100);
                $size_id = $faker->numberBetween(1,3);
                $quantity = $faker->numberBetween(1,15);
                $product = Product::select("price")->where("id",$product_id)->first();
                $unit_price = $product->price;
                $price = $product->price * $quantity;
                $total_price += $price;

                $datas[] = [
                    "product_id" => $product_id,
                    "size_id" => $size_id,
                    "quantity" => $quantity,
                    "price" => $unit_price,
                    "total_price" => $price,
                    "created_at" => new \DateTime(),
                    "updated_at" => new \DateTime()

                ];

            }

            $customer_id = $faker->numberBetween(1,100);
            $order_id = "ORD-".$faker->unique()->numberBetween(1000000000,9999999999);
            $status = $faker->numberBetween(1,5);

            $order = Order::create([
                "order_id" => $order_id,
                "customer_id" => $customer_id,
                "total_price" => $total_price,
                "status_id" => $status

            ]);

            foreach($datas as $data)
            {
                $data["order_id"] = $order->id;
                DB::table('order_products')->insert($data);
            }
        }
    }
}
