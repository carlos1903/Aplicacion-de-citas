<!--
    IMPORTANTE: Este archivo asume que se extiende de la plantilla 'guest' de Breeze
    que maneja la estructura básica, pero hemos inyectado el estilo CSS necesario aquí.
-->
<x-guest-layout>
    <style>
        /* Variables y Estilos Base */
        :root {
            --primary-color: #17a2b8; /* Azul Clínico */
            --primary-dark: #138496; /* Azul Oscuro al pasar el ratón */
            --bg-color: #f3f4f6; /* Fondo gris claro */
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        /* Contenedor Principal: Centrado */
        .login-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1.5rem; /* p-4 */
            background-color: var(--bg-color);
        }

        /* Título de la Aplicación */
        .app-title {
            font-size: 2.25rem; /* text-4xl */
            font-weight: 800; /* font-extrabold */
            color: var(--primary-color);
            letter-spacing: -0.05em; /* tracking-tight */
            margin-bottom: 0.5rem;
        }

        .app-title span {
            color: #4b5563; /* gray-700 */
        }

        .subtitle {
            font-size: 0.875rem; /* text-sm */
            color: #6b7280; /* gray-500 */
            text-align: center;
            margin-top: 0.25rem;
            margin-bottom: 1.5rem;
        }

        /* Tarjeta del Formulario */
        .login-card {
            width: 100%;
            max-width: 28rem; /* sm:max-w-md */
            padding: 2rem 1.5rem; /* px-6 py-8 */
            background-color: white;
            box-shadow: var(--shadow-light);
            overflow: hidden;
            border-radius: 1rem; /* rounded-2xl */
            border-top: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .login-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            font-size: 1.5rem; /* text-2xl */
            font-weight: 700; /* font-bold */
            color: #1f2937; /* gray-800 */
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        /* Estilos de Entrada (Input) */
        .input-group {
            margin-top: 1rem;
        }
        
        .input-label {
            display: block;
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
            color: #374151; /* gray-700 */
            margin-bottom: 0.25rem;
        }

        .text-input {
            display: block;
            width: 100%;
            padding: 0.65rem 0.75rem;
            border: 1px solid #d1d5db; /* border-gray-300 */
            border-radius: 0.5rem; /* rounded-lg */
            font-size: 1rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .text-input:focus {
            border-color: var(--primary-color);
            outline: 0;
            box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.3); /* focus:ring-[#17a2b8] */
        }

        /* Checkbox y Enlaces Inferiores */
        .footer-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }

        .checkbox-label {
            display: inline-flex;
            align-items: center;
            font-size: 0.875rem; /* text-sm */
            color: #4b5563; /* gray-600 */
        }

        .checkbox-input {
            border: 1px solid #d1d5db;
            border-radius: 0.25rem; /* rounded */
            color: var(--primary-color);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            margin-right: 0.5rem;
            /* Estilo de foco para el checkbox */
            --tw-ring-color: var(--primary-color);
        }

        .forgot-link {
            font-size: 0.875rem;
            color: #4b5563;
            text-decoration: underline;
            transition: color 0.15s ease;
        }

        .forgot-link:hover {
            color: #111827; /* gray-900 */
        }

        /* Botón de Submit */
        .submit-button-wrapper {
            margin-top: 1.5rem;
        }
        
        .submit-button {
            width: 100%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 0.5rem 1rem; /* px-4 py-2 */
            background-color: var(--primary-color);
            border: none;
            border-radius: 0.5rem; /* rounded-lg */
            font-weight: 600; /* font-semibold */
            font-size: 0.75rem; /* text-xs */
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em; /* tracking-widest */
            transition: background-color 0.15s ease, box-shadow 0.15s ease;
            cursor: pointer;
            box-shadow: var(--shadow-light);
        }

        .submit-button:hover {
            background-color: var(--primary-dark);
            box-shadow: var(--shadow-hover);
        }

        /* Enlace de Registro */
        .register-link-wrapper {
            margin-top: 1rem;
            text-align: center;
        }

        .register-link-wrapper p {
            font-size: 0.875rem;
            color: #4b5563;
        }

        .register-link {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        /* Clases de Breeze que se mantienen (mensajes de error) */
        .mb-4 { margin-bottom: 1rem; }
        .mt-2 { margin-top: 0.5rem; }

    </style>

    <!-- Contenedor Principal Centrado -->
    <div class="login-container">
        
        <!-- Logo/Título de la Clínica -->
        <div>
            <h1 class="app-title">
                <span>Clínica</span> APP
            </h1>
            <p class="subtitle">
                Portal de Acceso
            </p>
        </div>

        <!-- Tarjeta del Formulario -->
        <div class="login-card">
            
            <h2 class="card-header">Iniciar Sesión</h2>
            
            <!-- Session Status (manteniendo la clase de Breeze) -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="input-group">
                    <label for="email" class="input-label">{{ __('Correo Electrónico') }}</label>
                    <input 
                        id="email" 
                        class="text-input" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username" 
                    />
                    <!-- Reemplazar x-input-error si no usas componentes -->
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group">
                    <label for="password" class="input-label">{{ __('Contraseña') }}</label>
                    <input 
                        id="password" 
                        class="text-input"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password" 
                    />
                    <!-- Reemplazar x-input-error si no usas componentes -->
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me / Forgot Password -->
                <div class="footer-links">
                    <!-- Remember Me -->
                    <label for="remember_me" class="checkbox-label">
                        <input id="remember_me" type="checkbox" class="checkbox-input" name="remember">
                        <span>{{ __('Recordarme') }}</span>
                    </label>
                    
                    <!-- Forgot Password Link -->
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="submit-button-wrapper">
                    <button type="submit" class="submit-button">
                        {{ __('Ingresar al Sistema') }}
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Enlace de Registro (si aplica) -->
        @if (Route::has('register'))
            <div class="register-link-wrapper">
                <p>
                    ¿No tienes cuenta? 
                    <a href="{{ route('register') }}" class="register-link">
                        Regístrate
                    </a>
                </p>
            </div>
        @endif
    </div>
</x-guest-layout>