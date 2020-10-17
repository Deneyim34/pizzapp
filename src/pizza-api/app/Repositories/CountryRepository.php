<?php

namespace App\Repositories;

use App\Interfaces\IRepository;

use App\Models\Country;
use Illuminate\Pagination\Paginator;

use App\Http\Resources\CountryResource;
use App\Http\Resources\CountryResourceCollection;

class CountryRepository implements IRepository
{

    private $model;

    public function __construct(Country $model)
    {
        $this->model = $model;
    }

    public function getAll() :CountryResourceCollection
    {
        $countries = $this->model->all();
        return new CountryResourceCollection($countries);
    }

    public function getById($id) :CountryResource
    {
        $country = $this->model->where("id",$id)->first();
        return new CountryResource($country);
    }

    /**
     * Datayı sayfalar halinde listeler.
     *
     * @param  int  $page               // Sayfa numarası
     * @param  int  $totalPerPage       // Sayfada kaç adet data gösterileceği
     * @param  string  $search          // Aranılan kelime
     * @param  string  $searchType      // Aramanın hangi kolon üzerinde gerçekleştirileceği
     * @return CountryResourceCollection
     */

    public function getByPage($page = 1, $totalPerPage = 10,$search = "",$searchType = "") :CountryResourceCollection
    {

        Paginator::currentPageResolver(function() use ($page) {
            return $page;
        });


        if($search != "")
        {
            $countries =  $this->model->where('name', 'like', '%'.$search.'%')
            ->paginate($totalPerPage);
        }
        else{
            $countries =  $this->model->paginate($totalPerPage);
        }

        return new CountryResourceCollection($countries);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $country = $this->model->findOrFail($id);
        return $country->update($data);
    }

    public function destroy($id)
    {
        return $this->getById($id)->delete();
    }

}
