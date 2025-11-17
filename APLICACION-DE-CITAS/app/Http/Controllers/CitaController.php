<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * Muestra el listado de citas.
     */
    public function index()
    {
        // 1. Verificación de Acceso simple (Admin y Recepción pueden ver todas)
        $userRole = Auth::user()->role;
        if ($userRole !== 'Admin' && $userRole !== 'Recepción') {
            // Si no es Admin ni Recepción, asumimos que es Médico o Paciente
            return redirect()->route('dashboard')->with('error', 'Acceso denegado a la gestión completa de citas.');
        }

        // 2. Obtener todas las citas con sus relaciones
        $citas = Cita::with(['medico', 'paciente'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->get();
            
        return view('citas.index', compact('citas'));
    }

    /**
     * Muestra el formulario de creación de citas.
     */
    public function create()
    {
        $medicos = Medico::all();
        $pacientes = Paciente::all(); 
        
        return view('citas.create', compact('medicos', 'pacientes'));
    }

    /**
     * Almacena una nueva cita y valida la disponibilidad.
     */
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'motivo' => 'nullable|string|max:500',
        ]);
        
        $fecha = $request->fecha;
        $hora = $request->hora;
        $medicoId = $request->medico_id;
        // Se requiere Carbon para obtener el día de la semana
        try {
            // Aseguramos que la localización esté configurada (aunque depende de config/app.php)
            $diaSemana = Carbon::parse($fecha)->locale('es')->dayName; 
        } catch (\Exception $e) {
             // Fallback si la localización falla (usar nombre en inglés)
             $diaSemana = Carbon::parse($fecha)->dayName;
        }

        // ----------------------------------------------------
        // 2. VERIFICACIÓN DE DISPONIBILIDAD (Doble Chequeo)
        // ----------------------------------------------------

        // A. Chequear Disponibilidad General del Médico (Tabla 'disponibilidad')
        $disponible = DB::table('disponibilidad')
            ->where('medico_id', $medicoId)
            ->where('dia_semana', $diaSemana)
            ->where('hora_inicio', '<=', $hora)
            ->where('hora_fin', '>', $hora) 
            ->exists();

        if (!$disponible) {
            return back()->withInput()->withErrors(['hora' => "El médico no está disponible el {$diaSemana} a esa hora según su horario general."]);
        }
        
        // B. Chequear Conflicto con Citas Existentes (Tabla 'citas')
        $citaConflictiva = Cita::where('medico_id', $medicoId)
            ->where('fecha', $fecha)
            ->where('hora', $hora) 
            ->exists();

        if ($citaConflictiva) {
            return back()->withInput()->withErrors(['hora' => "El médico ya tiene una cita reservada a las {$hora} en esa fecha."]);
        }

        // ----------------------------------------------------
        // 3. Creación de la Cita
        // ----------------------------------------------------
        
        try {
            Cita::create([
                'paciente_id' => $request->paciente_id,
                'medico_id' => $medicoId,
                'fecha' => $fecha,
                'hora' => $hora,
                'motivo' => $request->motivo,
                'estado' => 'Pendiente', 
            ]);

            return redirect()->route('citas.index')->with('success', 'Cita agendada exitosamente para el paciente.');

        } catch (\Exception $e) {
             return back()->withInput()->withErrors(['general' => 'No se pudo crear la cita. Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Muestra una cita específica.
     */
    public function show(Cita $cita) 
    { 
        return view('citas.show', compact('cita'));
    }

    /**
     * Muestra el formulario para editar una cita.
     */
    public function edit(Cita $cita) 
    { 
        $medicos = Medico::all();
        $pacientes = Paciente::all();
        
        return view('citas.edit', compact('cita', 'medicos', 'pacientes'));
    }

    /**
     * Actualiza una cita existente (requiere revalidar disponibilidad si cambia fecha/hora/médico).
     */
    public function update(Request $request, Cita $cita) 
    { 
        // Lógica de validación similar a store, pero excluyendo la cita actual del conflicto de citas.
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'motivo' => 'nullable|string|max:500',
            'estado' => 'required|in:Pendiente,Confirmada,Cancelada,Finalizada',
        ]);

        $fecha = $request->fecha;
        $hora = $request->hora;
        $medicoId = $request->medico_id;
        $diaSemana = Carbon::parse($fecha)->locale('es')->dayName;

        // A. Chequear Disponibilidad General del Médico (Tabla 'disponibilidad') (Misma lógica que store)
        $disponible = DB::table('disponibilidad')
            ->where('medico_id', $medicoId)
            ->where('dia_semana', $diaSemana)
            ->where('hora_inicio', '<=', $hora)
            ->where('hora_fin', '>', $hora) 
            ->exists();

        if (!$disponible) {
            return back()->withInput()->withErrors(['hora' => "El médico no está disponible el {$diaSemana} a esa hora según su horario general."]);
        }

        // B. Chequear Conflicto con Citas Existentes (Excluyendo la cita actual)
        $citaConflictiva = Cita::where('medico_id', $medicoId)
            ->where('fecha', $fecha)
            ->where('hora', $hora) 
            ->where('id', '!=', $cita->id) // <--- EXCLUIMOS LA CITA QUE ESTAMOS EDITANDO
            ->exists();

        if ($citaConflictiva) {
            return back()->withInput()->withErrors(['hora' => "El médico ya tiene otra cita reservada a las {$hora} en esa fecha."]);
        }

        try {
            $cita->update($request->all());
            return redirect()->route('citas.index')->with('success', 'Cita actualizada exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['general' => 'No se pudo actualizar la cita. Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Elimina una cita.
     */
    public function destroy(Cita $cita) 
    { 
        try {
            $cita->delete();
            return redirect()->route('citas.index')->with('success', 'Cita eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar la cita. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Función para cambiar el estado de la cita (Ej: Confirmar, Cancelar)
     */
    public function cambiarEstado(Request $request, Cita $cita) 
    {
        // El acceso a esta función debe ser controlado en la vista o middleware.
        $request->validate(['estado' => 'required|in:Confirmada,Cancelada,Finalizada,Pendiente']);
        $cita->update(['estado' => $request->estado]);
        return back()->with('success', 'Estado de la cita actualizado a ' . $request->estado);
    }
}