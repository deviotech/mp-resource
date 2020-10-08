<?php

namespace App\Http\Controllers\Backend;

use App\Attribute;
use App\AttributeValue;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductImages;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all()
            ->load(['variation', 'variatonValues', 'attributes', 'attributeValues', 'brand', 'category'])
            ->map(function ($prod) {
                $prod->attribute = [
                    'ids' => $prod->attributes->pluck('id'),
                    'values' => $prod->attributeValues->pluck('id')
                ];
                $prod->stock = $prod->variation()->sum('stock_quantity');
                return $prod;
            })->map(function ($prod) {
                $prod->product_variations = [
                    'ids' => $prod->variation->pluck('id'),
                    'values' => $prod->variatonValues->pluck('id'),
                    'stocks' => $prod->variation()->pluck('stock_quantity'),
                    'variation_name' =>$prod->variatonValues->pluck('name'),
                ];
                return $prod;
            })->toArray();
        return response(['data' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->get('attribute');
        $variation = $request->get('variation');

        $brochure = $request->file('brochure');
        $name = time() . '_' . $brochure->getClientOriginalName();
        $path = 'images/multiple-images' . $name;
        $brochure->move('images/multiple-images', $name);

        $analysis = $request->file('analysis');
        $analysis_name = time() . '_' . $analysis->getClientOriginalName();
        $analysis_name_path = 'images/multiple-images' . $analysis_name;
        $analysis->move('images/multiple-images', $analysis_name);


        $product = Product::create([
            'name' => $request->get('name'),
            'category_id' => $request->get('category_id'),
            'description' => $request->get('description'),
            'brand_id' => $request->get('brand_id'),
            'price' => $request->get('price'),
            'sku' => $request->get('sku'),
            'brochure' =>  $name,
            'analysis' => $analysis_name,
        ]);


        foreach ($request->file('images') as $image) {
            $name = time() . '_' . $image->getClientOriginalName();
            $path = 'images/multiple-images' . $name;
            $image->move('images/multiple-images', $name);
            $product_images = ProductImages::create([
                'product_id' => $product->id,
                'path' => $name
            ]);
        }

        $stock_qty = [];
        for ($i = 0; $i < sizeof($variation['ids']); $i++) {
            $stock_qty[$variation['ids'][$i]] = ['stock_quantity' => $variation['stocks'][$i]];
        }

        $product->attributes()->attach($attributes['ids']);
        $product->attributeValues()->attach($attributes['values']);

        $product->variation()->attach($stock_qty);
        $product->variatonValues()->attach($variation['values']);

        return response('Product created');
    }

    public function update(Request $request, $id)
    {
        try {
            // dd($request->all());
            $product = Product::find($id);
            $attributes = $request->get('attribute');
            $variation = $request->get('variation');

            if ($request->hasFile('brochure')) {
                $brochure = $request->file('brochure');
                $name = time() . '_' . $brochure->getClientOriginalName();
                $path = 'images/multiple-images' . $name;
                $brochure->move('images/multiple-images', $name);
                $product->where('name', '=', 'brochure')->update([
                  'brochure' =>  $name,
                ]);
            }

            if ($request->hasFile('analysis')) {
                $analysis = $request->file('analysis');
                $analysis_name = time() . '_' . $analysis->getClientOriginalName();
                $analysis_name_path = 'images/multiple-images' . $analysis_name;
                $analysis->move('images/multiple-images', $analysis_name);
                $product->where('name', '=', 'analysis')->update([
                    'analysis' =>  $analysis_name,
                  ]);
            }

            $data = [
                'name' => $request->get('name'),
                'category_id' => $request->get('category_id'),
                'description' => $request->get('description'),
                'price' => $request->get('price'),
                'sku' => $request->get('sku'),
                'brand_id' => $request->get('brand_id'),
                //'brochure' =>  $name,
                //'analysis' => $analysis_name,
            ];
            $product->update($data);

            if ($request->hasFile('analysis')) {
                foreach ($request->file('images') as $image) {
                    $name = time() . '_' . $image->getClientOriginalName();
                    $path = 'images/multiple-images' . $name;
                    $image->move('images/multiple-images', $name);
                    $product_images = ProductImages::create([
                        'product_id' => $product->id,
                        'path' => $name
                    ]);
                }
            }

            $product->attributes()->detach($product->attributes->pluck('id'));
            $product->attributeValues()->detach($product->attributeValues->pluck('id'));

            $product->attributes()->attach($attributes['ids']);
            $product->attributeValues()->attach($attributes['values']);


            $stock_qty = [];
            for ($i = 0; $i < sizeof($variation['ids']); $i++) {
                $stock_qty[$variation['ids'][$i]] = ['stock_quantity' => $variation['stocks'][$i]];
            }

            $product->variation()->detach($product->variation->pluck('id'));
            $product->variatonValues()->detach($product->variatonValues->pluck('id'));

            $product->variation()->attach($stock_qty);
            $product->variatonValues()->attach($variation['values']);

            return response('Product updated');
        } catch (\Exception $exception) {
            return response(['message' => 'There was an error. Please try again later'], 500);
        }
    }
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->attributes()->detach($product->attributes->pluck('id'));
        $product->attributeValues()->detach($product->attributeValues->pluck('id'));

        $product->variation()->detach($product->variation->pluck('id'));
        $product->variatonValues()->detach($product->variatonValues->pluck('id'));

        ProductImages::where('product_id', $id)->delete();
        $product->delete();

        return response('Product deleted');
    }
}
