<?php

namespace App\Http\Controllers\Backend;

use App\Attribute;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class AttributeController extends Controller
{

    public function index()
    {
        $attribute = Attribute::all()->load('attributeValues')->toArray();
        return response(['data' => $attribute]);
    }


    public function store(Request $request)
    {
        try {
            $data = $request->all();

            Attribute::create($data);

            return response(['Attribute created']);
        } catch (\Exception $exception) {
            return response(['message' => 'There was an error. Please try again later'], 500);
        }
    }


    public function show(Attribute $attribute)
    {
        $attribute = $attribute->load('attributeValues')->toArray();

        return response(['data' => $attribute]);
    }


    public function update(Request $request, Attribute $attribute)
    {
        try {
            $data = [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ];

            $attribute->update($data);

            return response(['Attribute updated']);
        } catch (\Exception $exception) {
            return response(['message' => 'There was an error. Please try again later'], 500);
        }
    }


    public function destroy(Attribute $attribute)
    {
        try {
            $attribute->delete();

            return response(['Attribute delete']);
        } catch (\Exception $exception) {
            return response(['message' => 'There was an error. Please try again later'], 500);
        }
    }
}
