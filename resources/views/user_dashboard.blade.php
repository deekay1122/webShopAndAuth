@extends('layouts.myLayout')

@section('title', $user->name."'s Dashboard")

@section('content')
    @if ($purchasedItem)
    <div class="leftAlignContainer">
        @foreach ($purchasedItem as $item)
            <div class="heldItem">
                <div class="productName">{{ $products->where('id', $item->productId)->first()->name }} (<a href="/download/{{ $item->productId }}">Download</a>)</div>
            <div class="purchasedAt">Purchased at {{ $item->created_at }}</div>
            </div>
        @endforeach
    </div>
    @endif
    
@endsection