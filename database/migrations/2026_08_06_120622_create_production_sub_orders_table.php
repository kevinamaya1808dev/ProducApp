<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_sub_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained()->onDelete('cascade');

            // FK corregida: apunta a la tabla real "components", no a "recipe_components"
            $table->foreignId('component_id')->nullable()
                  ->constrained('components')->nullOnDelete();

            $table->string('proceso'); // Ej: Corte, Ensamblaje, Pintura
            $table->integer('quantity');
            $table->integer('completed_pieces')->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');

            // Marca explícita: esta suborden es la fase final que produce el producto terminado
            $table->boolean('es_ensamblaje')->default(false);

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_sub_orders');
    }
};