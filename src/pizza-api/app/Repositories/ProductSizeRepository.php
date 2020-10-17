<?php

namespace App\Repositories;

use App\Interfaces\IRepository;

use App\Models\ProductSize;
use Illuminate\Pagination\Paginator;

use App\Http\Resources\ProductSizeResource;
use App\Http\Resources\ProductSizeResourceCollection;

class ProductSizeRepository implements IRepository
{

    private $model;

    public function __construct(ProductSize $model)
    {
        $this->model = $model;
    }

    public function getAll() :ProductSizeResourceCollection
    {
        $statuses = $this->model->all();
        return new ProductSizeResourceCollection($statuses);
    }

    public function getById($id) :ProductSizeResource
    {
        $status = $this->model->where("id",$id)->first();
        return new ProductSizeResource($status);
    }

    /**
     * Datayı sayfalar halinde listeler.
     *
     * @param  int  $page               // Sayfa numarası
     * @param  int  $totalPerPage       // Sayfada kaç adet data gösterileceği
     * @param  string  $search          // Aranılan kelime
     * @param  string  $searchType      // Aramanın hangi kolon üzerinde gerçekleştirileceği
     * @return ProductSizeResourceCollection
     */

    public function getByPage(
        $page = 1,
        $totalPerPage = 10,
        $search = "",
        $searchType = ""
        ) :ProductSizeResourceCollection
    {

        Paginator::currentPageResolver(function() use ($page) {
            return $page;
        });

        if($search != "")
        {
            $statuses =  $this->model->where('name', 'like', '%'.$search.'%')
            ->paginate($totalPerPage);
        }
        else{
            $statuses =  $this->model->paginate($totalPerPage);
        }

        return new ProductSizeResourceCollection($statuses);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $status = $this->model->findOrFail($id);
        return $status->update($data);
    }

    public function destroy($id)
    {
        return $this->getById($id)->delete();
    }

}
