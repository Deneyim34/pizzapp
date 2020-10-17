<?php

namespace App\Models;

use App\Search\Searchable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use Searchable;

    public $resourceName = "Product";

    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'active'
    ];
}
