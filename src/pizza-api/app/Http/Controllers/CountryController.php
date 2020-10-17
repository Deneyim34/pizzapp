<?php

namespace App\Http\Controllers;

use App\Interfaces\IRepository;
use Illuminate\Http\Request;


class CountryController extends Controller
{
    private $country;

    public function __construct(IRepository $country)
    {
        $this->country = $country;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->has("page") && request()->page == "all")
        {
            return $this->country->getAll();
        }
        return $this->country->getByPage(
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
        return $this->country->create((array) $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->country->getById($id);
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
        return $this->country->update((array) $request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->country->destroy($id);
    }
}
