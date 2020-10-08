<?php

namespace App\Http\Controllers\Backend;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Order;
use App\Product;
use App\Variation;
use App\VariationValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use DB;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::with('product_images')->get();
        $products = ProductResource::collection($products);
        return response($products);
    }

    public function show(Product $product)
    {

        $product = new ProductResource($product->load(['variation.variationValues']));

        return response($product);
    }

    public function inCart()
    {
        return response(['data' => Auth::user()->carts->where('order_id', null)->load(['product', 'variation_value'])->toArray()]);
    }

    public function addToCart(Request $request)
    {
        $data = [
            'product_id' => $request->get('product'),
            'variation_value_id' => $request->get('variation_id'),
            'quantity' => $request->get('quantity'),
        ];

        $variation_id = $request->variation_id;

        $var = DB::table('product_variation')->where('variation_id', $variation_id)->where('product_id', $request->product)->first();
        
        $stock = $var->stock_quantity;
        $quantity = $request->quantity;
        if ($stock >= $quantity) {
            DB::table('product_variation')->where('id', $var->id)->update(
                [
                    'stock_quantity' => $var->stock_quantity - $request->quantity,
                ]
            );
            Auth::user()->carts()->create($data);
            $count = Auth::user()->inCart()->count();
        } else {
            return response([
                [], ['Product Stocks is Not Enough']
            ]);
        }
        return response([
            ["$count"],
            ['Product added to cart successfully']
        ]);
    }

    public function removeFromCart(Cart $cart)
    {
        $cart->delete();
        $count = Auth::user()->inCart()->count();
        return $count;
        return response(['Successfully removed from cart']);
    }

    public function makeOrder(Request $request)
    {
        // $prod = $request->get('product_id');
        // dd($prod);
        // dd($request->all());

        $carts_id = $request->get('carts_id');

        $data = [
            'user_id' => Auth::user()->id,
            'total_price' => $request->get('total_price'),
        ];

        $order = Order::create($data);

        Cart::whereIn('id', $carts_id)->update(['order_id' => $order->id]);


        return response(['Order created successfully']);
        //return ['redirect' => route('user.preorder')];
    }

    public function getOrders()
    {
        return response(['data' => Auth::user()->orders->load(['carts.product', 'carts.variation_value'])->toArray()]);
    }
    public function getcount()
    {
        $count = Auth::user()->inCart()->count();
        return $count;
    }
}
