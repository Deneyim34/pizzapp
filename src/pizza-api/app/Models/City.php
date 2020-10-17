<?php

namespace App\Models;
use App\Search\Searchable;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use Searchable;

    public $resourceName = "City";

    protected  $table = "cities";


}
