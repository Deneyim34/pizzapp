<?php

namespace App\Repositories;

use App\Interfaces\IRepository;

use App\Models\City;
use Illuminate\Pagination\Paginator;

use Elasticsearch\ClientBuilder;
use App\Search\ElasticsearchQueryBuilder;

use App\Http\Resources\CityResource;
use App\Http\Resources\CityResourceCollection;

class CityRepository implements IRepository
{
    use ElasticsearchQueryBuilder;

    private $model;
    private $elasticsearch;

    public function __construct(City $model)
    {
        $this->model = $model;
        $this->elasticsearch = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
    }

    public function getAll() : array
    {
        if(request()->has("country"))
        {
            $cities = $this->createSingleMatchQuery(request()->country,"country_id")->search(1000);
            //$cities = $this->model->where("country_id",request()->country)->get();
        }
        else{
            $cities = $this->model->all();
        }
        return $cities;


    }

    public function getById($id) :array
    {
        $city = $this->createSingleMatchQuery($id,"id")->search(1);
        /*$city = $this->model->where("id",$id)->first();*/
        return $city["data"][0];
    }

    /**
     * Datayı sayfalar halinde listeler.
     *
     * @param  int  $page               // Sayfa numarası
     * @param  int  $totalPerPage       // Sayfada kaç adet data gösterileceği
     * @param  string  $search          // Aranılan kelime
     * @param  string  $searchType      // Aramanın hangi kolon üzerinde gerçekleştirileceği
     * @return array // Şehirler
     */

    public function getByPage($page = 1, $totalPerPage = 10,$search = "",$searchType = "") :array
    {
        if($search != "")
        {
            $this->createBoolQuery()
                ->addShouldQuery()
                ->addLikeTOShouldQuery($search,["name"])
                ->addMinimumShouldMatch(1)
                ->addSortToQuery([["id"=>"desc"]]);
            /*$cities =  $this->model->where('name', 'like', '%'.$search.'%')
            ->paginate($totalPerPage);*/
        }
        else{
            $this->addSortToQuery([["id"=>"desc"]]);
            //$cities =  $this->model->paginate($totalPerPage);
        }

        $cities = $this->search($totalPerPage,$page);

        return [
            "data" => $cities["data"],
            "meta"=>[
                "current_page" => $page,
                "last_page" => ceil($cities["total"] / $totalPerPage)
                ]
        ];
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $city = $this->model->findOrFail($id);
        return response()->json($city->update($data));
    }

    public function destroy($id)
    {
        return response()->json($this->getById($id)->delete());
    }

}
