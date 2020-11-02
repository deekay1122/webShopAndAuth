<?php

use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Cashier;
use App\Models\Product;
use App\Models\User;
use App\Models\Sales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
// use Illuminate\Http\Response;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/products', function (){
    $products = Product::all();
    $msg = [];
    $cart = session('cart');

    $cartTotalQty = 0;

    if($cart) {
        foreach($cart as $item) {
            $cartTotalQty += $item['quantity'];
        }
    }

    return view('products',[
                            'products' => $products,
                            'msg' => $msg,
                            'cartTotalQty' => $cartTotalQty ]);
});

Route::get('/upload', function(Request $request){
    $errMsg = [];
    return view('uploadFile', [
        "errMsg" => $errMsg
    ]);
});

Route::post('/upload', function(Request $request){
    $folderName = $request->input('folderName');
    $dirName = 'storage' . '/videos' .'/' . $folderName;
    $errMsg = [];
    
    if($folderName == ""){
        array_push($errMsg, "please enter a product name");
        return view('uploadFile', [
            'errMsg' => $errMsg
        ]);
    }
    if(!is_dir($dirName)){
        mkdir($dirName);
        $files = $request->file('uploadedFile');
        foreach($files as $file){
            $fileName = $file->getClientOriginalName();
            $file->storeAs('public'. '/videos'. '/' . $folderName, $fileName);
        }
    
        return view('uploadFile', [
            'errMsg' => $errMsg,
        ]);
    } else {
        array_push($errMsg, "there is already a file with that name");
        return view('uploadFile', [
            'errMsg' => $errMsg,
        ]);
    }
})->name('upload');

Route::post('/charge', 'App\Http\Controllers\ChargeController@charge')->name('charge');

Route::get('/cashierTest', function(Request $request){
    $user = Cashier::findBillable('cus_IGt9ZJfdfmETKu');
    return $user->redirectToBillingPortal();
});

Route::get('/cart', 'App\Http\Controllers\ProductsController@showCart');

Route::get('/add-to-cart/{id}', 'App\Http\Controllers\ProductsController@addToCart');

Route::get('/remove-from-cart/{id}', 'App\Http\Controllers\ProductsController@remove');

Route::get('/checkout', function(Request $request){
    $totalAmount = 0;
    $totalQty = 0;
    $cart = session('cart');
    if($cart){
        foreach($cart as $item){
            $totalAmount += $item['price'];
            $totalQty += $item['quantity'];
        }
    }

    return view('checkout', [
        'cartTotalPrice' => $totalAmount,
        'cartTotalQty' => $totalQty
    ]);
})->middleware('auth');

Route::get('/user_dashboard', function (Request $request){
    $totalAmount = 0;
    $user = Auth::user();
    $cart = session('cart');
    if($cart){
        $totalAmount += $cart['quantity'];
    }

    $products = Product::all();
    $purchasedItem = Sales::all()->where('userId', $user->id);

    return view('user_dashboard', [
        'user' => $user,
        'cartTotalQty' => $totalAmount,
        'purchasedItem' => $purchasedItem,
        'products' => $products
    ]);
})->middleware('auth');

Route::get('/phpinfo', function(){
    echo(phpinfo());
});

Route::get('/download/{id}', function($id){
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    $product = Product::all()->where('id', $id)->first();
    $path = 'public/videos/' . $product->name;
    $files = Storage::files($path);
    $chargeId = Sales::all()->where('userId', Auth::user()->id)->where('productId', $id)->first()['chargeId'];

    $ch = $stripe->charges->retrieve(
        $chargeId
    );
    
    if($ch){
        return Storage::download($files[0]);
    } else {
        return back()->with('flash_message', 'You have not purchased that item');
    }
})->middleware('auth');