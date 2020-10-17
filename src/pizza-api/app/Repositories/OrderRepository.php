<?php

namespace App\Repositories;

use App\Interfaces\IRepository;
use App\Models\Order;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

use Elasticsearch\ClientBuilder;
use App\Search\ElasticsearchQueryBuilder;

use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderResourceCollection;

use App\Models\OrderProduct;
use App\Models\Product;

class OrderRepository implements IRepository
{
    use ElasticsearchQueryBuilder;

    private $model;
    private $elasticsearch;

    public function __construct(Order $model)
    {
        $this->model = $model;
        $this->elasticsearch = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
    }

    public function getAll() : OrderResourceCollection
    {
        $orders = $this->model;
        return new OrderResourceCollection($orders);
    }

    public function getById($id) : array
    {
        $order = $this->createSingleMatchQuery($id,"id")->search(1);
        /*$order = $this->model->where("id",$id)->first();*/
        return $order["data"][0];
    }

    /**
     * Datayı sayfalar halinde listeler.
     *
     * @param  int  $page               // Sayfa numarası
     * @param  int  $totalPerPage       // Sayfada kaç adet data gösterileceği
     * @param  string  $search          // Aranılan kelime
     * @param  string  $searchType      // Aramanın hangi kolon üzerinde gerçekleştirileceği
     * @return array // Siparişler
     */

    public function getByPage($page = 1, $totalPerPage = 10,$search = "",$searchType = "") : array
    {

        if($search != "")
        {

            //$where = [];
            if($searchType == "Customer")
            {
                $this->createSingleMatchQuery((int) $search,"customer_id");
                //$where = ["customer_id", '=' , $search];
            }
            else
            {
                $this->createBoolQuery()
                ->addShouldQuery()
                ->addLikeToShouldQuery($search,["order_id"])
                ->addMinimumShouldMatch(1);
                //$where = ["order_id", 'like' , '%'.$search.'%'];
            }

            /*$orders = $this->model->where(...$where)->orderBy("id","desc")
            ->paginate($totalPerPage);*/
        }
        else{

            //$orders = $this->model->orderBy("id","desc")->paginate($totalPerPage);
        }

        $orders = $this->addSortToQuery([["id" => "desc"]])->search($totalPerPage,$page);

        return [
            "data" => $orders["data"],
            "meta"=>[
                "current_page" => $page,
                "last_page" => ceil($orders["total"] / $totalPerPage)
                ]
        ];
    }

    public function create(array $datas) : object
    {
        $customer_id = request()->user()->id;

        $total_price = 0;
        $products = $datas["products"];

        /**
         * Ürünler tekrardan hesaplanıyor.
         */

        foreach($products as $key => $value)
        {
            $price = Product::find($value["id"])->price;
            $products[$key]["price"] = $price;
            $products[$key]["total_price"] = $price * $value["quantity"];
            $total_price += $products[$key]["total_price"];
        }

        /**
         * Sipariş datası oluşturuluyor.
         */

        $order = [
            "order_id" => "ORD-".date("dmYHis").$customer_id,
            "customer_id" => $customer_id,
            "vat" => 0.18,
            "total_price" => $total_price * 1.18,
            "status" => 1
        ];

        /**
         * Sipariş ve içerdiği ürünler kaydediliyor.
         */

        DB::beginTransaction();

        try {

            $new_order = $this->model->create($order);

            foreach($products as $data)
            {
                $data["order_id"] = $new_order->id;
                OrderProduct::create($data);
            }

            DB::commit();
            return response()->json($new_order,201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json("An error occurred during the process.",417);
        }
    }

    public function update(array $data, $id) : object
    {
        $order = $this->model->findOrFail($id);
        return response()->json($order->update($data));
    }

    public function destroy($id) : object
    {
        /**
         * Sİparişin var olup olmadığı kontrol ediliyor.
         */
        $order = $this->model->findOrFail($id);

        /**
         * Sipariş durumu "Teslim Edildi" ve ya "İptal Edildi" ise
         */
        if($order != null && ($order->status_id == 4 || $order->status_id == 5))
        {
            /**
             * Ürünlerin varlığı kontrol ediliyor ve siliniyor
             */
            $orderProducts = OrderProduct::where("order_id",$id)->get();

            DB::beginTransaction();

            try {

                if(count($orderProducts) > 0)
                {
                    OrderProduct::where("order_id",$id)->delete();
                }

                /**
                 * Sipariş siliniyor
                 */
                $this->model->where("id",$id)->delete();

                DB::commit();
                return response()->json("ok",204);

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json("An error occurred during the process.",417);
            }
        }
        else
        {
            return response()->json("This order still active.",417);
        }

    }



}
