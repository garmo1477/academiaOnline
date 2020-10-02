<section class="hero-cart-section set-bg" data-setbg="/img/bg.jpg">
    <div class="container">
        <div class="hero-cart-text text-white">
            {{-- si hay una variable disponible, en este caso title, se usa la variable, si no se usa el texto --}}            
            <h2>{{ $title ?? __('Aquí puedes ver tus productos') }}</h2>            
        </div>
        
    </div>
</section>