<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page') ?? 30;
        $results = QueryBuilder::for(Product::class)
        ->allowedFilters([
            AllowedFilter::partial('name', 'product_name')->default('')
        ])
        ->paginate($perPage);

        $pagination_info = [
            'current_page' => $results->currentPage(),
            'from' => $results->firstItem(),
            'next_page' => ($results->lastPage() > $results->currentPage()) ? $results->currentPage() + 1 : null,
            'prev_page' => ($results->currentPage() > 1) ? $results->currentPage() - 1 : null,
            'per_page' =>  $results->perPage(),
            'to' => (count($results->items()) > 0) ? ($results->firstItem() + $results->count() - 1) : null,
            'total' => $results->total(),
            'first_page' => 1, // always 1 isnt it?
            'last_page' => $results->lastPage(),
        ];

        return response()->json([
            'payload' => $results->items(),
            'pagination' => $pagination_info
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
