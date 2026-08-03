<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidence_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incidence_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quién realizó la acción
            $table->string('type'); // 'creacion', 'cambio_estado', 'cambio_prioridad', 'nota'
            $table->text('comment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidence_logs');
    }
};