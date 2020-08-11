<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name', 'price', 'stock', 'imageurl', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:sO',
        'updated_at' => 'datetime:Y-m-d\TH:i:sO',
    ];
}
