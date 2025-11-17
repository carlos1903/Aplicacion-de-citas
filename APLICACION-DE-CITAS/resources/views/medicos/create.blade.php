<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registrar Médico</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    <style>
        .container { max-width: 800px; margin: 50px auto; padding: 30px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], input[type="time"] {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;
        }
        .horario-row { display: flex; gap: 10px; align-items: center; margin-bottom: 8px; }
        .horario-row label { width: 100px; font-weight: normal; }
        .btn-submit { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-submit:hover { background-color: #218838; }
        .error { color: red; font-size: 0.9em; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧑‍⚕️ Registrar Nuevo Médico</h1>
        
        @if ($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('medicos.store') }}" method="POST">
            @csrf
            
            <h2>Datos Básicos y Login</h2>
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            </div>
            <div class="form-group">
                <label for="especialidad">Especialidad</label>
                <input type="text" id="especialidad" name="especialidad" value="{{ old('especialidad') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email (Usuario Login)</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>

            <hr style="margin: 25px 0;">

            <h2>Disponibilidad Semanal</h2>
            <p style="font-size: 0.9em; color: #666;">Define la franja horaria de trabajo para cada día. Usa formato HH:MM (ej: 08:00).</p>
            
            @foreach ($dias_semana as $index => $dia)
                <div class="horario-row">
                    <label>{{ $dia }}:</label>
                    <input type="hidden" name="horarios[{{ $index }}][dia]" value="{{ $dia }}">
                    
                    <div class="form-group" style="width: 50%;">
                        <label for="inicio_{{ $index }}" style="display: none;">Inicio</label>
                        <input type="time" id="inicio_{{ $index }}" name="horarios[{{ $index }}][inicio]" value="{{ old("horarios.$index.inicio") }}">
                    </div>
                    
                    <span style="font-weight: bold;"> - </span>
                    
                    <div class="form-group" style="width: 50%;">
                        <label for="fin_{{ $index }}" style="display: none;">Fin</label>
                        <input type="time" id="fin_{{ $index }}" name="horarios[{{ $index }}][fin]" value="{{ old("horarios.$index.fin") }}">
                    </div>
                </div>
            @endforeach

            <div class="form-group" style="margin-top: 30px;">
                <button type="submit" class="btn-submit">Guardar Médico y Horarios</button>
            </div>
        </form>
    </div>
</body>
</html>