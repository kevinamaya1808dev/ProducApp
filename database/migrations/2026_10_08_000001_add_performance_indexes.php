<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Índices para las consultas más frecuentes (filtros por estado, "piezas de hoy",
 * eficiencia semanal). Cada índice solo se crea si todavía no existe.
 */
return new class extends Migration
{
    private function indices(): array
    {
        return [
            'production_orders'    => [['status'], ['user_id', 'status']],
            'incidences'           => [['status'], ['user_id', 'created_at']],
            'registro_produccions' => [['created_at'], ['user_id', 'created_at']],
        ];
    }

    public function up(): void
    {
        foreach ($this->indices() as $tabla => $conjuntos) {
            foreach ($conjuntos as $columnas) {
                if (!Schema::hasIndex($tabla, $columnas)) {
                    Schema::table($tabla, fn (Blueprint $t) => $t->index($columnas));
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indices() as $tabla => $conjuntos) {
            foreach ($conjuntos as $columnas) {
                if (Schema::hasIndex($tabla, $columnas)) {
                    Schema::table($tabla, fn (Blueprint $t) => $t->dropIndex($columnas));
                }
            }
        }
    }
};
