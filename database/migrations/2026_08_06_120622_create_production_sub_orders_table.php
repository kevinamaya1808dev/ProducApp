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
    $table->foreignId('recipe_component_id')->nullable()->constrained('recipe_components')->onDelete('set null'); // <-- Conexión con componentes de la receta
    $table->string('proceso'); // Ej: Corte, Ensamblaje, Pintura
    $table->integer('quantity'); // Cantidad asignada a este proceso
    $table->integer('completed_pieces')->default(0);
    $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
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