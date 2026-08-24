<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Materiales o insumos en el almacén
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej. Tela Cordura, Cierre YKK #5
            $table->string('sku')->unique();
            $table->string('unit'); // Ej. metros, piezas, kg
            $table->decimal('stock_actual', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(5);
            $table->string('proveedor')->nullable();
            $table->timestamps();
        });

        // 2. Relación de Recetas: Qué material y cuánta cantidad consume un Producto (o suborden)
        Schema::create('product_recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->decimal('quantity_required', 10, 2); // Cantidad que gasta por cada unidad de producto
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_recipes');
        Schema::dropIfExists('materials');
    }
};