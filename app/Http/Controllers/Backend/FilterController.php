<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Brand;
use App\Category;
use App\Product;
use App\Http\Resources\ProductResource;
use App\Variation;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;

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
    public function multiselect(Request $request)
    {
        // dd($request->all());
        $brand_id = $request->brand_id;
        $category_id = $request->category_id;
        $viewsLimit = $request->view_filter;
        $sortFilter = $request->sort_filter;

        if ($request->brand_id) {
            $products = Product::where('brand_id', $brand_id);
        }
        if ($request->category_id) {
            if (!empty($products))
                $products = $products->whereIn('category_id', $category_id);
            else
                $products = Product::whereIn('category_id', $category_id);
        }
        //view limit sorting
        if (!empty($products)) {
            $products = $products->with('product_images');
        } else {
            $products = Product::with('product_images');
        }

        if ($sortFilter) {
            if ($sortFilter == 1)
                $products = $products->orderBy('name');
            else if ($sortFilter == 3)
                $products = $products->orderBy('created_at');
            else if ($sortFilter == 4)
                $products = $products->orderBy('id', 'asc');
            else if ($sortFilter == 5)
                $products = $products->orderBy('id', 'desc');
            else
            {
                $products = $products->orderBy('name');
            } 
        } else {
            $products = $products->orderBy('name');
        }

        if ($viewsLimit) {
            if ($viewsLimit == 1)
                $products = $products->paginate(16);
            else if ($viewsLimit == 2)
                $products = $products->paginate(32);
            else if ($viewsLimit == 3)
                $products = $products->paginate(48);
            else
                $products = $products->paginate(16);
        } else {
            $products = $products->paginate(16);
        }

        $products = ProductResource::collection($products);
        return response(['data' => $products]);
    }
}
