<?php

namespace App\Http\Controllers;

use App\Interfaces\IRepository;
use Illuminate\Http\Request;


class CityController extends Controller
{
    private $city;

    public function __construct(IRepository $city)
    {
        $this->city = $city;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->has("country"))
        {
            return $this->city->getAll();
        }
        return $this->city->getByPage(
            request()->has("page") ? request()->page : 1,
            request()->has("total") ? request()->total : 10,
            request()->has("search") ? request()->search : "",
            request()->has("type") ? request()->type : ""
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        return $this->city->getByPage(
            request()->has("page") ? request()->page : 1,
            request()->has("total") ? request()->total : 10,
            request()->has("search") ? request()->search : "",
            request()->has("type") ? request()->type : ""
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->city->create((array) $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->city->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->city->update((array) $request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->city->destroy($id);
    }
}
