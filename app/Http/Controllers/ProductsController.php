<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    public function showCart(Request $request) {
        $cartTotalQty = 0;
        $cartTotalPrice = 0;

        $cart = session('cart');

        if($cart) {
            foreach($cart as $item) {
                $cartTotalQty += $item['quantity'];
                $cartTotalPrice += $item['price'];
            }
        }
        return view('cart', [
            'cartTotalQty' => $cartTotalQty,
            'cartTotalPrice' => $cartTotalPrice,
            'cart' => $cart
        ]);
    }

    public function addToCart($id)
    {
        $product = Product::find($id);
        if(!$product) {
            abort(404);
        }
        $cart = session()->get('cart');
        // if cart is empty the this is the first product in cart
        if(!$cart) {
            $cart = [
                $id => [
                    "itemId" => $id,
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    "photo" => $product->photo_url
                ]
            ];

            session()->put('cart', $cart);
            return redirect()->back()->with('flash_message', 'Successfully added to the cart');
        }

        if(!isset($cart[$id])){
            $cart[$id] = [
                "itemId" => $id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "photo" => $product->photo_url
            ];
            session()->put('cart', $cart);
            return redirect()->back()->with('flash_message', 'Successfully added to the cart');
        }

        if(isset($cart[$id])){
            return redirect()->back()->with('flash_message', 'The item already in cart');
        }
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('flash_message', 'Product removed successfully');
    }
    public function clear() {
        $cart = session('cart');
        foreach ($cart as $iterm){
            if(isset($item)){
                unset($item);
            }
        }
        session()->put('cart', $cart);
    }
}
