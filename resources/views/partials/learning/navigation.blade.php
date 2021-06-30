<!-- Header section -->
<header class="header-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="site-logo">
                    <img src="/img/logo.png" alt="">
                </div>
                <div class="nav-switch">
                    <i class="fa fa-bars"></i>
                </div>
            </div>
            <div class="col-lg-9 col-md-9">
                
                @guest
                    <a href="#" id="login-button" class="site-btn header-btn">{{ __('Acceder') }}</a>
                    @include('partials.learning.modals.login')
                @else
                    <a href="{{ route('logout') }}"
                    {{-- evento para que no se ejecute el evento tipico de los enlaces sino q haga lo q viene después --}}
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
                        class="site-btn header-btn"
                    >{{ __('Salir') }}</a>

                    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:none;">
                        @csrf
                    </form>
                @endguest

                <nav class="main-menu">
                    <ul>
                        <li><a href="{{ route("welcome") }}">{{ __("Inicio") }}</a></li>
                        <li><a href="{{ route("courses.index") }}">{{ __('Cursos') }}</a></li>
                        <li><a href="blog.html">{{ __('Noticias') }}</a></li>
                        <li><a href="contact.html">{{ __('Contacto') }}</a></li>
                        @teacher
                            <li>
                                <a class="brand-text" href="{{ route('teacher.index') }}">{{ __('Profesor') }}</a>
                            </li>
                        @endteacher
                        @auth
                            <li>
                                <a class="brand-text" href="{{ route('student.index') }}">{{ __('Estudiante') }}</a>
                            </li>
                        @endauth
                        
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- Header section end -->

@push("js")
    <script>
        //si la validación falla o el usuario no ha puesto bien las credenciales
        @if(session('error-login'))
        //se abre la ventana modal de login.blade (dentro de modals)
            $('#login-modal').modal();
        @endif
        //si clicamos en el botón con este id más arriba se abre la ventana modal
        $('#login-button').on('click', function(e){
            e.preventDefault();
            $('#login-modal').modal();
        })
    </script>
@endpush
