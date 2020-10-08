<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Brand;
use App\Category;
use App\Product;
use App\Http\Resources\ProductResource;
use App\Variation;

class FilterController extends Controller
{
    public function brandfilter()
    {
        $brands = Brand::all();
        return response(['data' => $brands]);
    }
    public function categories_filter()
    {
        $categories = Category::all();
        return response(['data' => $categories]);
    }
    public function variation_list()
    {
        $variation = Variation::all();
        return response(['data' => $variation]);
    }

    public function filterproduct($id)
    {
        //$products = Product::with('product_images')->get();
        $products = Product::where('brand_id', $id)->with('product_images')->get();
        $products = ProductResource::collection($products);
        return response(['data' => $products]);
    }
    public function category_filter($id)
    {
        $products = Product::where('category_id', $id)->with('product_images')->get();
        $products = ProductResource::collection($products);
        return response(['data' => $products]);
    }
    public function variation_filter($id)
    {
        $products = Product::where('variation_id', $id)->with('product_images')->get();
        $products = ProductResource::collection($products);
        return response(['data' => $products]);
    }
    public function sorting(Request $request,$id)
    {
        if ($id == 1) {
            $products = Product::with('product_images')->orderBy('name')->get();
            $products = ProductResource::collection($products);
            return response(['data' => $products]);
        }
        if ($id == 2) {
            $products = Product::with('product_images')->orderBy('price')->get();
            $products = ProductResource::collection($products);
            return response(['data' => $products]);
        }
        if ($id == 3) {
            $products = Product::with('product_images')->orderBy('created_at')->get();
            $products = ProductResource::collection($products);
            return response(['data' => $products]);
        }
        if ($id == 4) {
        }
        if ($id == 5) {
            $products = Product::with('product_images')->orderBy('id', 'asc')->get();
            $products = ProductResource::collection($products);
            return response(['data' => $products]);
        }
        if ($id == 6) {
            $products = Product::with('product_images')->orderBy('id', 'desc')->get();
            $products = ProductResource::collection($products);
            return response(['data' => $products]);
        }
    }
    public function views_sorting($id)
    {
        if ($id == 1) {
            $products = Product::with('product_images')->paginate(16);
            $products = ProductResource::collection($products);
            return response(['data' => $products]);
        }
        if ($id == 2) {
            $products = Product::with('product_images')->paginate(32);
            $products = ProductResource::collection($products);
            return response(['data' => $products]);
        }
        if ($id == 3) {
            $products = Product::with('product_images')->paginate(48);
            $products = ProductResource::collection($products);
            return response(['data' => $products]);
        }
    }
}
