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
        Schema::create('production_sub_order_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('production_sub_order_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('estacion')->nullable(); // Estación desde donde participa el operario
    $table->integer('pieces_contributed')->default(0); // Piezas aportadas por este operario en esta suborden
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_sub_order_user');
    }
};
