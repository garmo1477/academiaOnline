@extends('layouts.learning')

@section('hero')
    @include('partials.learning..hero_cart')
@endsection

@section('content')
    {{-- inject lo q hace es injectar variables que sean una clase (en este caso) que
    automaticamente crea una estancia y poder disfrutar de ella en un archivo blade. Así podemos usar métodos de esa
    clase--}}
    @inject('cart', 'App\Services\Cart')
    {{-- entonces aquí llamamos al método que devuelve el contenido todal del carrito
    --}}
    <div class="container">
        @include('partials.learning.cart_content')

        @if ($cart->hasProducts())
            <div class="row">
                <div class="col-12 mb-5">
                    <a href="{{ route('checkout_form') }}" class="site-btn float-right">
                        {{ __('Procesar pedido') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
