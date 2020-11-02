@extends('layouts.myLayout')
@section('title', 'Cart')
@section('content')
    @if ($cart)
    <div class="leftAlignContainer">
        <p>Now in cart</p>
        @foreach ($cart as $item)
        <div class="heldItem">
            <div class="productName">{{ $item['name'] }}</div>
            <div class="price">{{ $item['price'] }} JPY</div>
            <div class="deleteCartItem"><a href="/remove-from-cart/{{ $item['itemId'] }}">Remove Item</a></div>
        </div> 
        @endforeach
        <p>Cart total: {{ $cartTotalPrice }} JPY</p>
        <div class="checkoutButton">
            <a href="/checkout">Goto Checkout</a>
        </div>
    </div>
    @endif
@endsection