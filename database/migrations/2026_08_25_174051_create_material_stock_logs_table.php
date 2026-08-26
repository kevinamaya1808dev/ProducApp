<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            // nullOnDelete: si el usuario se elimina más adelante, el registro del
            // ingreso de stock se conserva (no se pierde el historial de auditoría).
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('quantity_added', 10, 2);
            $table->decimal('stock_resultante', 10, 2); // stock_actual justo después de este ingreso
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_stock_logs');
    }
};