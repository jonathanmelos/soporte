@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Logo centrado -->
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="HM innova soporte" style="height: 80px;">
            </div>

            <!-- Frase célebre -->
            <div id="frase" class="text-center mb-4" style="font-size: 1.5rem; font-weight: 600; color: #333; padding: 20px; border: 2px solid #ddd; border-radius: 8px;">
                <!-- La frase será cargada aquí -->
            </div>

            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Formulario de login -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Array de frases célebres
    const frases = [
        "No te preguntes qué pueden hacer tus compañeros por ti. Pregúntate qué puedes hacer tú por ellos. – Magic Johnson",
        "La motivación surge de trabajar en lo que nos gusta. También, de trabajar con las personas que nos gustan. — Sheryl Sandberg",
        "El desorden no es más que decisiones pospuestas. — Barbara Hemphill",
        "Por cada minuto dedicado a la organización, se gana una hora. — Benjamin Franklin",
        "Si no sabes adónde vas, acabarás en otro sitio. — Yogui Berra",
        "La forma en que tratamos a las personas es un reflejo directo de cómo nos sentimos con nosotros mismos. — Paulo Coelho",
        "Ten paciencia. Todas las cosas están difíciles antes de ser fáciles. — Saadi Shirazi",
        "La planificación a largo plazo no es pensar en decisiones futuras, sino en el futuro de las decisiones presentes. — Peter Drucker",
        "La clave de la productividad es el enfoque. — Brian Tracy",
        "La creatividad es la inteligencia divirtiéndose. — Albert Einstein",
        "El éxito no es la clave de la felicidad. La felicidad es la clave del éxito. Si amas lo que haces, tendrás éxito. — Albert Schweitzer",
        "La única forma de hacer un gran trabajo es amar lo que haces. — Steve Jobs",
        "No cuentes los días, haz que los días cuenten. — Muhammad Ali"
    ];

    // Función para mostrar una frase aleatoria
    const fraseAleatoria = frases[Math.floor(Math.random() * frases.length)];

    // Mostrar la frase en el div
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('frase').innerText = fraseAleatoria;
    });
</script>

@endsection
