<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    // Corrección de formato para SQL Server
    protected $dateFormat = 'Y-m-d\TH:i:s';

    protected $fillable = [
        'paciente_id', 
        'medico_id', 
        'fecha', 
        'hora', 
        'estado', 
        'motivo',
        'anotaciones_medicas',
        'diagnostico',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }
}