<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use App\Models\User;
use App\Models\Sales;

use Illuminate\Http\Request;

class ChargeController extends Controller
{
    /*単発決済用のコード*/
    public function charge(Request $request)
    {
        $user = Auth::user();
        $totalAmount = 0;
        $cart = session('cart');
        
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $customer = Customer::create(array(
                'email' => $request->stripeEmail,
                'source' => $request->stripeToken
            ));

            foreach($cart as $item){
                $totalAmount += $item['price'];
            }

            $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount' => $totalAmount,
                'currency' => 'jpy'
            ));

            foreach($cart as $item){
                $sales = new Sales;
                $sales->userId = $user->id;
                $sales->stripeId = $customer->id;
                $sales->chargeId = $charge->id;
                $sales->productId = $item['itemId'];
                $sales->price = $item['price'];
                $sales->save();
            }
    
            session()->forget('cart');

            return back();
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
