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
        Schema::create('incidences', function (Blueprint $table) {
    $table->id();
    $table->foreignId('production_order_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Operario que reporta
    $table->string('title');
    $table->text('description');
    $table->enum('status', ['pendiente', 'en_proceso', 'resuelta'])->default('pendiente');
    $table->enum('importance', ['baja', 'media', 'alta'])->default('baja');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidences');
    }
};