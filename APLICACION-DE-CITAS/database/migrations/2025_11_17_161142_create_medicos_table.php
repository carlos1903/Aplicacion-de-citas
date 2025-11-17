<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicos', function (Blueprint $table) {
            $table->id();
            // Enlace al usuario que se autentica (rol Médico)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('nombre');
            $table->string('especialidad');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicos');
    }
};