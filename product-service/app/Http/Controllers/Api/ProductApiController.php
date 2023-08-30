<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\CreateProductJob;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductApiController extends Controller
{
    public function index(){
        $products = Product::all();
        return response($products, Response::HTTP_OK);
    }
    public function store(Request $request){
        $product = Product::create($request->only('name','stock'));
        CreateProductJob::dispatch($product->toArray())->onQueue('default');
        return response($product, Response::HTTP_OK);
    }
}
