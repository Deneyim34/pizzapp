<?php

namespace App\Models;

use App\Search\Searchable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    use Searchable;

    public $resourceName = "Order";

    protected $fillable = [
        'order_id',
        'customer_id',
        'vat',
        'total_price',
        'status_id'
    ];

    protected $guarded = [
        'order_id',
        'customer_id',
        'vat',
        'total_price'
    ];

    public function OrderProducts()
    {
        return $this->hasMany('App\Models\OrderProduct','order_id','id');
    }

    public function User()
    {
        return $this->belongsTo('App\Models\User','customer_id','id');
    }

    public function Status()
    {
        return $this->belongsTo('App\Models\Status','status_id','id');
    }

}
