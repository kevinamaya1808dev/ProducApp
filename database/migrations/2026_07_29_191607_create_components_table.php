<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            
            // Relación con tu tabla categories existente
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('restrict'); // 'restrict' evita que borres una categoría si tiene componentes
            
            $table->string('name'); // Ej. Tela Mezclilla 12oz
            $table->string('sku')->unique()->nullable(); // Ej. MAT-001
            $table->string('base_unit', 20); // Ej. m², pzas, m
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};