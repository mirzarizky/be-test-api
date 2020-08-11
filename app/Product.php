<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'name', 'price', 'stock', 'imageurl', 'created_by', 'updated_by'
    ];

    public function create()
    {
        $this->created_by = Auth::id();

        return parent::create();
    }


    // public function update()
    // {
    //     $this->updated_by = Auth::id();

    //     return parent::update();
    // }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:sO',
        'updated_at' => 'datetime:Y-m-d\TH:i:sO',
    ];
}
