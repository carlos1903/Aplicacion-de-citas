<!--
    Este dashboard asume que estás usando la plantilla base de Laravel Breeze (layouts/app.blade.php).
    Es necesario que el archivo resources/views/layouts/app.blade.php exista para que este dashboard funcione.
-->
<script src="https://cdn.tailwindcss.com"></script>
<style>
    /* Configuración de color principal para el tema de la clínica */
    :root {
        --primary: #17a2b8; /* Azul Clínico */
        --secondary: #28a745; /* Verde para Acciones Positivas */
    }
    .text-clinic { color: var(--primary); }
    .bg-clinic-light { background-color: #f0f8ff; }
    .border-clinic { border-color: var(--primary); }
    .card-shadow { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
    .btn-action { transition: all 0.2s; }
    .btn-action:hover { transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
</style>

<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-4 sm:p-8 font-sans">
        <div class="max-w-7xl mx-auto">

            <!-- Header de Bienvenida -->
            <header class="bg-white card-shadow rounded-xl p-6 mb-8 border-t-4 border-clinic">
                <h2 class="text-3xl font-extrabold text-gray-800 mb-2">
                    ¡Bienvenido/a, {{ Auth::user()->name }}!
                </h2>
                <p class="text-xl text-clinic font-medium">
                    Panel de Control de {{ Auth::user()->role }}
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Fecha: {{ \Carbon\Carbon::now()->locale('es')->isoFormat('D MMMM YYYY') }}
                </p>
            </header>

            <!-- Sección de Accesos Rápidos/Módulos -->
            <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Card 1: Gestión de Médicos -->
                <a href="{{ route('medicos.index') }}" class="bg-white p-6 rounded-xl card-shadow hover:bg-clinic-light border-l-4 border-clinic btn-action">
                    <div class="flex items-center space-x-4">
                        <svg class="w-8 h-8 text-clinic" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H9a1 1 0 01-1-1v-1a4 4 0 014-4h.25M15 17h6m-3 3v-6"></path></svg>
                        <div>
                            <p class="text-lg font-bold text-gray-800">Médicos</p>
                            <p class="text-sm text-gray-500">Administrar personal médico y horarios.</p>
                        </div>
                    </div>
                </a>

                <!-- Card 2: Gestión de Citas -->
                <a href="{{ route('citas.index') }}" class="bg-white p-6 rounded-xl card-shadow hover:bg-clinic-light border-l-4 border-secondary btn-action">
                    <div class="flex items-center space-x-4">
                        <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <div>
                            <p class="text-lg font-bold text-gray-800">Citas</p>
                            <p class="text-sm text-gray-500">Agendar, confirmar y cancelar citas.</p>
                        </div>
                    </div>
                </a>

                <!-- Card 3: Gestión de Pacientes -->
                <a href="{{ route('pacientes.index') }}" class="bg-white p-6 rounded-xl card-shadow hover:bg-clinic-light border-l-4 border-clinic btn-action">
                    <div class="flex items-center space-x-4">
                        <svg class="w-8 h-8 text-clinic" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H8m2-9a4 4 0 11-8 0 4 4 0 018 0zM17 10h2m-2 4h2m-2 4h2M12 18h.01"></path></svg>
                        <div>
                            <p class="text-lg font-bold text-gray-800">Pacientes</p>
                            <p class="text-sm text-gray-500">Registro y gestión de expedientes.</p>
                        </div>
                    </div>
                </a>

                <!-- Card 4: Gestión de Usuarios y Roles (Solo Admin) -->
                @if (Auth::user()->role == 'Admin')
                <a href="#" class="bg-white p-6 rounded-xl card-shadow hover:bg-blue-50 border-l-4 border-blue-600 btn-action">
                    <div class="flex items-center space-x-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <div>
                            <p class="text-lg font-bold text-gray-800">Configuración</p>
                            <p class="text-sm text-gray-500">Gestión de usuarios y permisos.</p>
                        </div>
                    </div>
                </a>
                @endif

            </section>
            
            <!-- Sección de Estadísticas Clave (Métricas) -->
            <section>
                <h3 class="text-2xl font-semibold text-gray-700 mb-4 border-b pb-2">Resumen Semanal</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Métrica 1: Citas Pendientes -->
                    <div class="bg-white p-6 rounded-xl card-shadow border-l-4 border-yellow-500">
                        <p class="text-sm font-medium text-gray-500">Citas Pendientes Hoy</p>
                        <p class="text-4xl font-extrabold text-yellow-600 mt-1">4</p>
                    </div>

                    <!-- Métrica 2: Médicos Activos -->
                    <div class="bg-white p-6 rounded-xl card-shadow border-l-4 border-clinic">
                        <p class="text-sm font-medium text-gray-500">Total Médicos Activos</p>
                        <p class="text-4xl font-extrabold text-clinic mt-1">{{ \App\Models\Medico::count() }}</p>
                    </div>

                    <!-- Métrica 3: Pacientes Registrados -->
                    <div class="bg-white p-6 rounded-xl card-shadow border-l-4 border-gray-500">
                        <p class="text-sm font-medium text-gray-500">Pacientes Registrados</p>
                        <p class="text-4xl font-extrabold text-gray-700 mt-1">{{ \App\Models\Paciente::count() }}</p>
                    </div>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>