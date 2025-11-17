<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disponibilidad', function (Blueprint $table) {
            $table->id();
            // Clave foránea para vincular al médico
            $table->foreignId('medico_id')->constrained()->onDelete('cascade'); 
            $table->string('dia_semana', 10); // Ej: Lunes
            $table->time('hora_inicio'); // Ej: 08:00:00
            $table->time('hora_fin'); // Ej: 17:00:00
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disponibilidad');
    }
};