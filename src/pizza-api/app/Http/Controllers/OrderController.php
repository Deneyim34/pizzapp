<?php

namespace App\Http\Controllers;

use App\Interfaces\IRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private $order;

    public function __construct(IRepository $order)
    {
        $this->order = $order;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->user()->role > 1)
        {
            return $this->order->getByPage(
                request()->has("page") ? request()->page : 1,
                request()->has("total") ? request()->total : 10,
                request()->has("search") ? request()->search : "",
                request()->has("type") ? request()->type : ""
            );
        }
        else{
            return $this->order->getByPage(
                request()->has("page") ? request()->page : 1,
                request()->has("total") ? request()->total : 10,
                request()->user()->id,"Customer"
            );
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(count($request->params["products"]) > 0)
        {
            return $this->order->create($request->params);
        }
        else{
            return response()->json("There is no product in your cart");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->order->getById($id);
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
        $request->validate([
            'status_id' => 'required'
        ]);
        return $this->order->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->order->destroy($id);
    }
}
