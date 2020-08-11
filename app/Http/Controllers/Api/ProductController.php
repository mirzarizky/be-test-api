<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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
        $perPage = $request->input('per_page') ?? 30; // default 30
        $limit = $request->input('limit');
        $offset = $request->input('offset');

        $query = QueryBuilder::for(Product::class)
        ->defaultSort('-product_id')
        ->allowedSorts(['product_id', 'product_name', 'price', 'stock', 'created_at', 'updated_at'])
        ->allowedFields(['product_id', 'product_name', 'price', 'stock', 'imageurl', 'created_by', 'updated_by', 'created_at', 'updated_at'])
        ->allowedFilters([
            // AllowedFilter::partial('name', 'product_name')->default('')
            'product_name'
        ]);

        $query_limited_offseted = $this->getQueryLimitOffset($query, $limit, $offset);

        $results = !empty($limit) ? $query_limited_offseted->get()  : $query->paginate($perPage);

        $pagination_info = empty($limit) ? $this->getPagination($results) : null;

        return response()->json([
            'status_code' => 200,
            'status_message' => 'Success',
            'description' => '',
            'offset' => $offset,
            'limit' => $limit,
            'query_url' => $request->fullUrlWithQuery($request->query()),
            'count' => count(!empty($limit) ? $results : $results->items()),
            'payload' => !empty($limit) ? $results : $results->items(),
            'pagination' => $pagination_info
        ]);
    }

    private function getQueryLimitOffset($query, $limit, $offset)
    {
        if(!empty($limit) && !empty($offset)) {
            return $query->take($limit)->skip($offset);
        }
        else {
            if(!empty($limit)) {
                $query->take($limit);
            }
            if(!empty($offset)) {
                $query->skip($offset);
            }

            return $query;
        }
    }

    private function getPagination($results)
    {
        return [
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'imageurl' => 'required|url',
        ]);

        $form_data = Arr::add($request->only(['product_name', 'price', 'stock', 'imageurl']), 'created_by', Auth::id());

        $product = Product::create($form_data);

        return response()->json([
            'status_code' => 200,
            'status_message' => 'Success',
            'description' => '',
            'payload' => ['product_id' => $product->product_id]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json([
            'status_code' => 200,
            'status_message' => 'Success',
            'description' => '',
            'payload' => $product
        ]);
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
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'imageurl' => 'required|url',
        ]);

        $form_data = Arr::add($request->only(['product_name', 'price', 'stock', 'imageurl']), 'updated_by', Auth::id());

        $product->update($form_data);

        return response()->json([
            'status_code' => 200,
            'status_message' => 'Success',
            'description' => '',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status_code' => 200,
            'status_message' => 'Success',
            'description' => ''
        ]);
    }
}
