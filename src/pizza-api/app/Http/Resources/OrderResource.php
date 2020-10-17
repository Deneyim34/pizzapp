<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Order;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        return $this->ForList();
    }

    public function ForList()
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'vat' => $this->vat,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'order_time' => $this->created_at->format('d.m.Y H:i:s'),
            'products' => new OrderProductResourceCollection(Order::find($this->id)->OrderProducts)
        ];
    }

    public function toData()
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'vat' => $this->vat,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'customer_id' => $this->customer_id,
            'order_time' => $this->created_at->format('d.m.Y H:i:s'),
            'customer' => new UserResource(Order::find($this->id)->User),
            'products' => new OrderProductResourceCollection(Order::find($this->id)->OrderProducts)
        ];
    }
}
