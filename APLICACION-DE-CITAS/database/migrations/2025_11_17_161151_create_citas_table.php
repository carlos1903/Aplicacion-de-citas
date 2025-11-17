<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            
            // Claves foráneas sin CASCADE para evitar el error 42000 de SQL Server
            $table->foreignId('paciente_id')->constrained()->onDelete('no action'); 
            $table->foreignId('medico_id')->constrained()->onDelete('no action'); 

            $table->date('fecha');
            $table->time('hora');
            $table->text('motivo')->nullable();
            
            // Estado inicial: Pendiente
            $table->string('estado')->default('Pendiente'); 
            
            // Campos para el historial clínico básico
            $table->text('anotaciones_medicas')->nullable(); 
            $table->text('diagnostico')->nullable();
            
            $table->timestamps();

            // Índice único para evitar sobre-reserva en el mismo instante y médico
            $table->unique(['medico_id', 'fecha', 'hora']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};