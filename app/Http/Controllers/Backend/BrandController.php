<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Brand;

class BrandController extends Controller
{

    public function index()
    {
        $brand = Brand::all();

        return response(['data' => $brand]);
    }


    public function store(Request $request)
    {
        try {
            $data = $request->all();

            Brand::create($data);

            return response(['Brand created']);
        } catch (\Exception $exception) {
            return response(['message' => 'There was an error. Please try again later'], 500);
        }
    }


    // public function show(Brand $brand)
    // {
    //     $attribute = $brand->load('attributeValues')->toArray();

    //     return response(['data' => $attribute]);
    // }


    public function update(Request $request, Brand $brand)
    {
        try {
            $data = [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ];

            $brand->update($data);

            return response(['Brands updated']);
        } catch (\Exception $exception) {
            return response(['message' => 'There was an error. Please try again later'], 500);
        }
    }


    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();

            return response(['Brand delete']);
        } catch (\Exception $exception) {
            return response(['message' => 'There was an error. Please try again later'], 500);
        }
    }
}
