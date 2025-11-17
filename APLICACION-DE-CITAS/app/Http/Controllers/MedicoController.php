<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medico;
use App\Models\Disponibilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // AUTORIZACIÓN: Chequeo directo para evitar errores de middleware.
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Acceso no autorizado. Se requiere el rol de Administrador.');
        }

        $medicos = Medico::with('user', 'disponibilidad')->get();
        return view('medicos.index', compact('medicos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // AUTORIZACIÓN: Chequeo directo para evitar errores de middleware.
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Acceso no autorizado. Se requiere el rol de Administrador.');
        }
        
        return view('medicos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // AUTORIZACIÓN: Chequeo directo para evitar errores de middleware.
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Acceso no autorizado. Se requiere el rol de Administrador.');
        }
        
        // 1. Validación de Datos (Nombres y Especialidad deben coincidir)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            
            'especialidad' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            
            // Validación de horario como array, el chequeo de inicio/fin va después
            'horario' => ['required', 'array'],
        ]);

        // 2. Crear Usuario, Médico y Disponibilidad dentro de una Transacción
        DB::beginTransaction();

        try {
            // A. Crear el Usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'Medico', // Rol fijo para médicos
            ]);

            // B. Crear el Perfil de Médico
            $medico = Medico::create([
                'user_id' => $user->id,
                'especialidad' => $request->especialidad,
                'telefono' => $request->telefono,
            ]);

            // C. Guardar la Disponibilidad
            $disponibilidades = [];
            foreach ($request->horario as $diaSemana => $horario) {
                if (!isset($horario['libre']) && isset($horario['inicio']) && isset($horario['fin'])) {
                    
                    // Chequeo de lógica de horario
                    if ($horario['inicio'] >= $horario['fin']) {
                        DB::rollBack();
                        return back()->withInput()->withErrors(['horario' => "La hora de inicio debe ser anterior a la hora de fin para el día {$diaSemana}."]);
                    }

                    $disponibilidades[] = [
                        'medico_id' => $medico->id,
                        'dia_semana' => $diaSemana,
                        'hora_inicio' => $horario['inicio'],
                        'hora_fin' => $horario['fin'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            if (!empty($disponibilidades)) {
                Disponibilidad::insert($disponibilidades);
            }
            
            DB::commit();

            return redirect()->route('medicos.index')->with('success', 'El médico ' . $request->name . ' ha sido registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'Error inesperado al crear el médico. Detalles: ' . $e->getMessage()]);
        }
    }

    // Métodos restantes (obligatorios para Route::resource)
    public function show(Medico $medico) {}
    public function edit(Medico $medico) {}
    public function update(Request $request, Medico $medico) {}
    public function destroy(Medico $medico) {}
}