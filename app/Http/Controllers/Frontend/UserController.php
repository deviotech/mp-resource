<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Newsletter;
use App\Product;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function news() {
        return view('user.news');
    }

    public function shop() {
        return view('user.shop');
    }

    public function product(Product $product) {
        $product = Product::where('id', $product->id)->first();
        return view('user.product', get_defined_vars());
    }

    public function preorder() {
        return view('user.preorder');
    }

    public function cart() {
        return view('user.cart');
    }

    public function add_to_newsletter(Request $request)
    {
        $request->validate([
            'email'=>'required|email|unique:users,email'
        ]);
         $newsletter =  Newsletter::create([
           'email'=> $request->email
       ]);
       sendMail([
        'view' => 'email.initial_newsletter',
        'to' => $newsletter->email,
        'subject' => 'Your are Added to Newsletter Subscription',
        'data' => []
    ]);
       return redirect()->back();
    }
}
