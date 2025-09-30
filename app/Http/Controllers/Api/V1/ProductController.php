<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::paginate(10);
        return response()->json([
            'status' => 200,
            'message' => 'Successfully fetched products',
            'data' => ProductResource::collection($products)
        ]);
    }
    public function show(Product $product)
    {
        $detailProduct = new ProductResource($product);
        return response()->json([
            'status' => 200,
            'message' => 'Successfully fetched product',
            'data' => $detailProduct
        ]);
    }
}
