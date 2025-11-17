<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;
    
    // Corrección de formato para SQL Server
    protected $dateFormat = 'Y-m-d\TH:i:s';

    protected $fillable = [
        'nombre',
        'especialidad',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disponibilidad()
    {
        return $this->hasMany(Disponibilidad::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}