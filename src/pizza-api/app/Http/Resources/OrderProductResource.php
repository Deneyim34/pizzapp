<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\OrderProduct;

class OrderProductResource extends JsonResource
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

        $product = OrderProduct::find($this->id)->Product;
        return [
            "id" => $this->id,
            "name" => $product->name,
            "image" => $product->image,
            "description" => $product->description,
            "quantity" => $this->quantity,
            "price" => $this->price,
            "total_price" => $this->total_price,
            "size" => OrderProduct::find($this->size_id)->ProductSizes->short_name
        ];
    }
}
