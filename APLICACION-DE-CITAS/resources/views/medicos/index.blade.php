<!--
    Vista para crear un nuevo perfil de médico y su usuario asociado.
    Se extiende de la plantilla base 'layouts.app'.
-->
<script src="https://cdn.tailwindcss.com"></script>
<style>
    /* Estilos basados en el tema de la clínica */
    :root {
        --primary: #17a2b8; /* Azul Clínico */
        --primary-dark: #138496;
        --secondary: #28a745; /* Verde para Acciones Positivas */
    }
    .text-clinic { color: var(--primary); }
    .border-clinic { border-color: var(--primary); }
    .card-shadow { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }

    /* Botón de Guardar */
    .btn-save {
        background-color: var(--secondary);
        transition: all 0.2s;
    }
    .btn-save:hover {
        background-color: #218838;
        transform: translateY(-1px);
    }
    /* Botón de Cancelar */
    .btn-cancel {
        background-color: #6b7280; /* Gray-500 */
        transition: all 0.2s;
    }
    .btn-cancel:hover {
        background-color: #4b5563; /* Gray-700 */
    }
</style>

<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-4 sm:p-8 font-sans">
        <div class="max-w-4xl mx-auto">

            <!-- Encabezado y Navegación -->
            <header class="mb-6">
                <h1 class="text-3xl font-extrabold text-gray-800 border-b-2 pb-2 text-clinic">
                    Registrar Nuevo Médico
                </h1>
                <p class="text-gray-600 mt-2">Completa los datos de usuario, perfil médico y su disponibilidad inicial.</p>
            </header>

            <!-- Manejo de Errores y Sesión -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">¡Atención!</strong>
                    <span class="block sm:inline">Hay problemas con los datos proporcionados.</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulario Principal -->
            <div class="bg-white card-shadow rounded-xl p-6 md:p-8 border-t-4 border-clinic">
                <form method="POST" action="{{ route('medicos.store') }}">
                    @csrf

                    <!-- --------------------------------- -->
                    <!-- SECCIÓN 1: DATOS DE USUARIO (AUTH) -->
                    <!-- --------------------------------- -->
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-1">1. Información de Acceso</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <!-- Nombre Completo -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-clinic focus:ring-clinic">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Correo Electrónico -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico (Login)</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-clinic focus:ring-clinic">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña Temporal</label>
                            <input type="password" name="password" id="password" required 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-clinic focus:ring-clinic">
                            @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-clinic focus:ring-clinic">
                            @error('password_confirmation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- --------------------------------- -->
                    <!-- SECCIÓN 2: DATOS DE PERFIL MÉDICO -->
                    <!-- --------------------------------- -->
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-1 mt-8">2. Datos de Perfil</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <!-- Especialidad -->
                        <div>
                            <label for="especialidad" class="block text-sm font-medium text-gray-700">Especialidad Principal</label>
                            <select name="especialidad" id="especialidad" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-clinic focus:ring-clinic">
                                <option value="" disabled selected>Seleccione una especialidad</option>
                                {{-- Lista de Especialidades Comunes --}}
                                @php
                                    $especialidades = [
                                        'Cardiología', 'Dermatología', 'Pediatría', 
                                        'Medicina General', 'Ginecología', 'Oftalmología',
                                        'Odontología', 'Psicología', 'Nutrición'
                                    ];
                                @endphp
                                @foreach ($especialidades as $esp)
                                    <option value="{{ $esp }}" {{ old('especialidad') == $esp ? 'selected' : '' }}>{{ $esp }}</option>
                                @endforeach
                            </select>
                            @error('especialidad') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Teléfono/Celular (Opcional) -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono/Celular</label>
                            <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-clinic focus:ring-clinic">
                            @error('telefono') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- --------------------------------- -->
                    <!-- SECCIÓN 3: DISPONIBILIDAD INICIAL -->
                    <!-- --------------------------------- -->
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-1 mt-8">3. Horario Inicial</h2>
                    <p class="text-sm text-gray-500 mb-4">Define el horario de disponibilidad semanal del médico.</p>
                    
                    <div class="space-y-4">
                        @php
                            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                        @endphp

                        @foreach ($dias as $dia)
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 bg-gray-50 p-3 rounded-lg border">
                                <label class="w-24 font-semibold text-gray-700">{{ $dia }}</label>
                                
                                <div class="flex items-center gap-4 flex-grow">
                                    <label class="text-sm text-gray-600">De:</label>
                                    <input type="time" name="horario[{{ $dia }}][inicio]" 
                                        value="{{ old("horario.{$dia}.inicio", '08:00') }}" 
                                        class="rounded-lg border-gray-300 shadow-sm w-full sm:w-1/3 focus:border-clinic focus:ring-clinic text-sm">

                                    <label class="text-sm text-gray-600">Hasta:</label>
                                    <input type="time" name="horario[{{ $dia }}][fin]" 
                                        value="{{ old("horario.{$dia}.fin", '17:00') }}" 
                                        class="rounded-lg border-gray-300 shadow-sm w-full sm:w-1/3 focus:border-clinic focus:ring-clinic text-sm">
                                </div>
                                
                                {{-- Campo para marcar como día libre/no disponible --}}
                                <div class="flex items-center ml-auto">
                                    <input type="checkbox" name="horario[{{ $dia }}][libre]" id="horario_{{ $dia }}_libre" value="1" 
                                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500"
                                        {{ old("horario.{$dia}.libre") ? 'checked' : '' }}>
                                    <label for="horario_{{ $dia }}_libre" class="ml-2 text-sm text-red-600">Día Libre</label>
                                </div>
                            </div>
                        @endforeach
                        
                        {{-- Mensaje de error global para el horario --}}
                        @error('horario') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>


                    <!-- Botones de Acción -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('medicos.index') }}" class="btn-cancel text-white px-6 py-2 rounded-lg font-semibold shadow-md">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-save text-white px-6 py-2 rounded-lg font-semibold shadow-lg">
                            Registrar Médico
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>