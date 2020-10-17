<?php

namespace App\Repositories;

use App\Interfaces\IRepository;

use App\Models\User;
use Illuminate\Pagination\Paginator;

use Elasticsearch\ClientBuilder;
use App\Search\ElasticsearchQueryBuilder;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IRepository
{
    use ElasticsearchQueryBuilder;

    private $model;
    private $elasticsearch;

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->elasticsearch = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
    }

    public function getAll() :UserResourceCollection
    {
        $users = $this->model->all();
        return new UserResourceCollection($users);
    }

    public function getById($id) :UserResource
    {
        if(request()->user()->role == 2)
        {
            $user = $this->model->where("id",$id)->first();
        }
        else{
            $user = $this->model->where("id",request()->user()->id)->first();
        }

        return new UserResource($user);
    }

    /**
     * Datayı sayfalar halinde listeler.
     *
     * @param  int  $page               // Sayfa numarası
     * @param  int  $totalPerPage       // Sayfada kaç adet data gösterileceği
     * @param  string  $search          // Aranılan kelime
     * @param  string  $searchType      // Aramanın hangi kolon üzerinde gerçekleştirileceği
     * @return array // Kullanıcılar
     */

    public function getByPage($page = 1, $totalPerPage = 10,$search = "",$searchType = "")
    {

        $this->createBoolQuery()
            ->addMustQuery()
            ->addEqualToMustQuery("1",["role"]);

        if($search != "")
        {
            $this->addShouldQuery()
            ->addLikeToShouldQuery($search,["name","surname"])
            ->addMinimumShouldMatch(1);

            /*$users =  $this->model->where('role','=','1')
            ->where('name', 'like', '%'.$search.'%')
            ->orWhere('surname', 'like', '%'.$search.'%')
            ->where('role', '=', '1')
            ->orderBy("id","desc")
            ->paginate($totalPerPage);*/
        }
        else{
            //$users =  $this->model->where('role','=','1')->orderBy("id","desc")->paginate($totalPerPage);
        }
        $users = $this->addSortToQuery([["id" => "desc"]])->search($totalPerPage,$page);

        return response()->json(
                [
                    "data" => $users["data"],
                    "meta"=>[
                        "current_page" => $page,
                        "last_page" => ceil($users["total"] / $totalPerPage)
                        ]
                ]
                ,200
            );

    }

    public function create(array $data)
    {
        $data["password"] = Hash::make($data["password"]);
        return response()->json($this->model->create($data),201);
    }

    public function update(array $data, $id)
    {
        if(isset($data["email"]))
        {
            unset($data["email"]);
        }


        $user = $this->model->findOrFail($id);
        if(array_key_exists("password",$data))
        {
            $data["password"] = Hash::make($data["password"]);
        }

        return response()->json($user->update($data),200);
    }

    public function destroy($id) :object
    {
        return response()->json($this->getById($id)->delete(),204);
    }

}
