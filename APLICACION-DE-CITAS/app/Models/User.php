<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Define el formato de los timestamps (created_at, updated_at) para evitar el error 22007 de SQL Server.
     * SQL Server no siempre acepta el formato por defecto de Laravel con milisegundos.
     */
    protected $dateFormat = 'Y-m-d\TH:i:s'; // CLAVE PARA SQL SERVER

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Asegúrate de que 'role' esté aquí
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // Relación 1:1 con Perfil Médico
    public function medico()
    {
        return $this->hasOne(Medico::class);
    }

    // Relación 1:1 con Perfil Paciente
    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }
}