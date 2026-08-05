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
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            
            // Relación con categories (ahora nullable desde el inicio)
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->onDelete('restrict');
            
            // Relación con component_types añadida directamente
            $table->foreignId('component_type_id')
                  ->nullable()
                  ->constrained('component_types')
                  ->nullOnDelete();
            
            $table->string('name'); // Ej. Tela Mezclilla 12oz
            $table->string('sku')->unique()->nullable(); // Ej. MAT-001
            $table->string('base_unit', 20); // Ej. m², pzas, m
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};