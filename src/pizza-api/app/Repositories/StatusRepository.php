<?php

namespace App\Repositories;

use App\Interfaces\IRepository;

use App\Models\Status;
use Illuminate\Pagination\Paginator;

use App\Http\Resources\StatusResource;
use App\Http\Resources\StatusResourceCollection;

class StatusRepository implements IRepository
{

    private $model;

    public function __construct(Status $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        $statuses = $this->model->all();
        return new StatusResourceCollection($statuses);
    }

    public function getById($id)
    {
        $status = $this->model->where("id",$id)->first();
        return new StatusResource($status);
    }

    /**
     * Datayı sayfalar halinde listeler.
     *
     * @param  int  $page               // Sayfa numarası
     * @param  int  $totalPerPage       // Sayfada kaç adet data gösterileceği
     * @param  string  $search          // Aranılan kelime
     * @param  string  $searchType      // Aramanın hangi kolon üzerinde gerçekleştirileceği
     * @return StatusResourceCollection
     */

    public function getByPage($page = 1, $totalPerPage = 10,$search = "",$searchType = "")
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

        return new StatusResourceCollection($statuses);
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
