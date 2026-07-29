<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('component_recipe', function (Blueprint $table) {
            $table->id();
            
            // Relación con tu tabla recipes existente
            $table->foreignId('recipe_id')
                  ->constrained('recipes')
                  ->onDelete('cascade'); // Si borras la receta, se borran sus componentes asociados
                  
            // Relación con la nueva tabla components
            $table->foreignId('component_id')
                  ->constrained('components')
                  ->onDelete('cascade');
                  
            // Guardamos la cantidad con decimales (ej. 2.5 metros)
            // 8 dígitos en total, 2 decimales
            $table->decimal('quantity', 8, 2); 
            
            $table->timestamps();
            
            // Evitamos que un mismo componente se agregue dos veces a la misma receta
            $table->unique(['recipe_id', 'component_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('component_recipe');
    }
};