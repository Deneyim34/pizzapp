<?php

namespace App\Models;
use App\Search\Searchable;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use Searchable;

    public $resourceName = "District";

    protected  $table = "districts";

}
