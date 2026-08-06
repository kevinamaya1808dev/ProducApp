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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Operario asignado
            $table->string('proceso'); // ej: 'Ensamblaje', 'Corte', 'Acabado'
            $table->string('estacion')->nullable();
            $table->integer('quantity');
            $table->integer('completed_pieces')->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
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