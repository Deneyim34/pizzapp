<?php

namespace App\Repositories;

use App\Interfaces\IRepository;

use App\Models\District;
use Illuminate\Pagination\Paginator;

use Elasticsearch\ClientBuilder;
use App\Search\ElasticsearchQueryBuilder;

use App\Http\Resources\DistrictResource;
use App\Http\Resources\DistrictResourceCollection;

class DistrictRepository implements IRepository
{
    use ElasticsearchQueryBuilder;

    private $model;
    private $elasticsearch;

    public function __construct(District $model)
    {
        $this->model = $model;
        $this->elasticsearch = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
    }

    public function getAll() : array
    {
        if(request()->has("country"))
        {
            $districts = $this->createBoolQuery()
                ->addMustQuery()
                ->addEqualToMustQuery([request()->country,request()->city],["country_id","city_id"])
                ->addSortToQuery([["id"=>"desc"]])
                ->search(1000);
            /*$districts = $this->model
                ->where("country_id",request()->country)
                ->where("city_id",request()->city)
                ->get();*/
        }
        else{
            $districts = $this->model->all();
        }
        return $districts;
    }

    public function getById($id) :array
    {
        $district = $this->createSingleMatchQuery($id,"id")->search(1);
        /*$district = $this->model->where("id",$id)->first();*/
        return $district["data"][0];
    }

    /**
     * Datayı sayfalar halinde listeler.
     *
     * @param  int  $page               // Sayfa numarası
     * @param  int  $totalPerPage       // Sayfada kaç adet data gösterileceği
     * @param  string  $search          // Aranılan kelime
     * @param  string  $searchType      // Aramanın hangi kolon üzerinde gerçekleştirileceği
     * @return array // İlçeler
     */

    public function getByPage($page = 1, $totalPerPage = 10,$search = "",$searchType = "") : array
    {

        if($search != "")
        {
            $this->createBoolQuery()
                ->addShouldQuery()
                ->addLikeTOShouldQuery($search,["name"])
                ->addMinimumShouldMatch(1)
                ->addSortToQuery([["id"=>"desc"]]);
            /*$districts =  $this->model->where('name', 'like', '%'.$search.'%')
            ->paginate($totalPerPage);*/
        }
        else{
            $this->addSortToQuery([["id"=>"desc"]]);
            //$districts =  $this->model->paginate($totalPerPage);
        }
        $districts = $this->search($totalPerPage,$page);

        return [
            "data" => $districts["data"],
            "meta"=>[
                "current_page" => $page,
                "last_page" => ceil($districts["total"] / $totalPerPage)
                ]
            ];
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $district = $this->model->findOrFail($id);
        return $district->update($data);
    }

    public function destroy($id)
    {
        return $this->getById($id)->delete();
    }

}
