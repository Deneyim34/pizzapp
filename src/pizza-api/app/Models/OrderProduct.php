<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    //
    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'quantity',
        'price',
        'total_price'
    ];

    public function Product()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }

    public function ProductSizes()
    {
        return $this->belongsTo('App\Models\ProductSize','size_id','id');
    }
}
