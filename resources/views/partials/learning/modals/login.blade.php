<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">{{ __('Acceder con tu cuenta') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (session('error-login'))
                    <div class="form-errors">
                        <p>{{ session('error-login') }}</p>
                    </div>
                @endif
                {{-- login form --}}
                <form action="{{ route('login') }}" method="POST" class="signup-form">
                    @csrf
                    <input 
                        type="text"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="{{ __('Correo Electrónico') }}"
                    />
                    <input 
                        type="password"
                        name="password"
                        placeholder="{{ __('Contraseña') }}"
                    />
                    <button
                        type="submit"
                        class="site-btn btn-block"
                    >
                        {{ __('Iniciar sesión') }}
                    </button>
                </form>
            </div>
        </div>
    </div>    
</div>