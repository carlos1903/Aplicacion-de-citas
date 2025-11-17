<!DOCTYPE html>
<html lang="es">
<head>
    <title>Agendar Nueva Cita</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    <style>
        .container { max-width: 600px; margin: 50px auto; padding: 30px; border: 1px solid #e0e0e0; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background-color: #fff; }
        h1 { color: #17a2b8; border-bottom: 3px solid #17a2b8; padding-bottom: 10px; margin-bottom: 30px; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; }
        select, input[type="date"], input[type="time"], textarea {
            width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; box-sizing: border-box;
            font-size: 16px;
        }
        .btn-submit { background-color: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 18px; width: 100%; transition: background-color 0.3s; }
        .btn-submit:hover { background-color: #218838; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb; }
        .error-list { list-style: none; padding-left: 0; margin: 0; }
        .error-list li { margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗓️ Agendar Nueva Cita</h1>
        
        @if ($errors->any())
            <div class="alert-error">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('citas.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="paciente_id">Paciente</label>
                <select id="paciente_id" name="paciente_id" required>
                    <option value="">Seleccione un Paciente</option>
                    @foreach ($pacientes as $paciente)
                        <option value="{{ $paciente->id }}" {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                            {{ $paciente->nombre }} {{ $paciente->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="medico_id">Médico</label>
                <select id="medico_id" name="medico_id" required>
                    <option value="">Seleccione un Médico</option>
                    @foreach ($medicos as $medico)
                        <option value="{{ $medico->id }}" {{ old('medico_id') == $medico->id ? 'selected' : '' }}>
                            {{ $medico->nombre }} - {{ $medico->especialidad }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="fecha" value="{{ old('fecha') }}" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="hora">Hora</label>
                    <input type="time" id="hora" name="hora" value="{{ old('hora') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="motivo">Motivo de la Cita</label>
                <textarea id="motivo" name="motivo" rows="3">{{ old('motivo') }}</textarea>
            </div>

            <button type="submit" class="btn-submit">Agendar Cita</button>
        </form>
    </div>
</body>
</html>