@extends('layouts.myLayout')

@section('content')
<form action="{{ route('charge') }}" method="POST">
    {{ csrf_field() }}
            <script
                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="{{ env('STRIPE_KEY') }}"
                    data-amount={{ $cartTotalPrice }}
                    data-name="Stripe Demo"
                    data-label="決済をする"
                    data-description="Online course about integrating Stripe"
                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                    data-locale="auto"
                    data-currency="JPY">
            </script>
</form>
@endsection