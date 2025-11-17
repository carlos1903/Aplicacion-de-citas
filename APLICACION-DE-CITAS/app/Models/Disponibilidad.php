<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
    use HasFactory;

    // Corrección de formato para SQL Server
    protected $dateFormat = 'Y-m-d\TH:i:s';

    protected $table = 'disponibilidad'; 
    
    protected $fillable = [
        'medico_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }
}