<?php

namespace App\Repositories;

use App\Interfaces\IRepository;

use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Pagination\Paginator;

use Elasticsearch\ClientBuilder;
use App\Search\ElasticsearchQueryBuilder;

use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductResourceCollection;

use Intervention\Image\Facades\Image;

class ProductRepository implements IRepository
{
    use ElasticsearchQueryBuilder;

    private $model;
    private $elasticsearch;

    public function __construct(Product $model)
    {
        $this->model = $model;
        $this->elasticsearch = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
    }

    public function getAll() : array
    {
        $products = $this->model->all();
        return $products;
    }

    public function getById($id) :array
    {
        if(request()->user()->role == 1)
        {
            $product = $this->createBoolQuery()
                    ->addMustQuery()
                    ->addEqualToMustQuery([$id,"1"],["id","active"])
                    ->addSortToQuery([["id"=>"desc"]])
                    ->search();
        }
        else{
            $product = $this->createBoolQuery()
                    ->addMustQuery()
                    ->addEqualToMustQuery([$id],["id"])
                    ->addSortToQuery([["id"=>"desc"]])
                    ->search();
        }

        //$product = $this->model->where("id",$id)->first();
        return $product["data"][0];
    }

    /**
     * Datayı sayfalar halinde listeler.
     *
     * @param  int  $page               // Sayfa numarası
     * @param  int  $totalPerPage       // Sayfada kaç adet data gösterileceği
     * @param  string  $search          // Aranılan kelime
     * @param  string  $searchType      // Aramanın hangi kolon üzerinde gerçekleştirileceği
     * @return array // Ürünler
     */

    public function getByPage($page = 1, $totalPerPage = 10,$search = "",$searchType = "") : array
    {
        if($search != "")
        {
            if(request()->user()->role == 1)
            {
                $this->createBoolQuery()
                ->addShouldQuery()
                ->addLikeTOShouldQuery($search,["name","description"])
                ->addMinimumShouldMatch(1)
                ->addMustQuery()
                ->addEqualToMustQuery(["1"],["active"])
                ->addSortToQuery([["id"=>"desc"]]);
            }
            else
            {
                $this->createBoolQuery()
                ->addShouldQuery()
                ->addLikeTOShouldQuery($search,["name","description"])
                ->addMinimumShouldMatch(1)
                ->addSortToQuery([["id"=>"desc"]]);
            }

            /*$products =  $this->model->where('name', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%')
            ->paginate($totalPerPage);*/
        }
        else{
            if(request()->user()->role == 2)
            {
                $this->addSortToQuery([["id"=>"desc"]]);
            }
            else
            {
                $this->createBoolQuery()
                        ->addMustQuery()
                        ->addEqualToMustQuery(["1"],["active"])
                        ->addSortToQuery([["id"=>"desc"]]);
            }
            //$products =  $this->model->paginate($totalPerPage);
        }

        $products = $this->search($totalPerPage,$page);

        return [
                "data" => $products["data"],
                "meta"=>[
                        "current_page" => $page,
                        "last_page" => ceil($products["total"] / $totalPerPage)
                    ]
            ];
    }

    public function create(array $data)
    {
        if(request()->file('image')) //Test değilse upload et test ise data gelmez sadece string gelir
        $data["image"] =  $this->uploadImage();

        return response()->json($this->model->create($data),201);
    }


    public function update(array $data, $id)
    {
        $product = $this->model->findOrFail($id);
        $datas =  (array) request()->all();

        /**
         * Resim varsa oluştur ve resize et
         */

        if(request()->has('image'))
        $datas["image"] =  $this->uploadImage();

        return response()->json($product->update($datas),200);
    }

    public function destroy($id)
    {
        $product = $this->model->findOrFail($id);
        if($product)
        {
            if(OrderProduct::where("product_id" , $id)->count() > 0)
            {
                $product = $this->model->findOrFail($id);
                return response()->json($product->update(["active" => 0]),204);
            }
            else
            {
                return response()->json($product->delete(),204);
            }
        }


    }

    private function uploadImage() :string
    {
        /**
         * Dosya bilgisi alınıyor
         */
        $image = request()->file('image');

        /**
         * İsim belirleniyor
         */
        $image_name = time() . '.' . $image->getClientOriginalExtension();

        /**
         * Upload edilecek klasör yolu belirleniyor
         */
        $destinationPath = public_path('/uploads/products/');

        /**
         * Resim oluşturuluyor
         */
        $resize_image = Image::make($image->getRealPath());

        /**
         * Yeniden boyutlandırılıyor ve kaydediliyor.
         */
        $resize_image->resize(300, 300, function($constraint){
        $constraint->aspectRatio();
        })->save($destinationPath . '/' . $image_name);


        return $image_name;
    }

}
