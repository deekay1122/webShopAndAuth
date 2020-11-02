@extends('layouts.myLayout')

@section('content')
    @if ($products)
    <div class="container">
        @foreach ($products as $product)
        <div class="productDiv">
            <div class="productName">
                {{ $product->name }}
            </div>
            <div class="productDescription">
                {{ $product->description }}
            </div>
            <div class="productPrice">
                {{ $product->price }} JPY
            </div>
        <button><a href="{{ url('add-to-cart/'.$product->id) }}">Add To Cart</a></button>
        </div>
        @endforeach
    </div>
    @endif
@endsection