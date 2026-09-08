<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registro_produccions', function (Blueprint $table) {
            // Nullable porque una orden sin subórdenes registra su avance
            // directo (sin fase asociada). nullOnDelete: si se borra la
            // suborden, el registro histórico de producción se conserva.
            $table->foreignId('sub_order_id')
                ->nullable()
                ->after('production_order_id')
                ->constrained('production_sub_orders')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('registro_produccions', function (Blueprint $table) {
            $table->dropForeign(['sub_order_id']);
            $table->dropColumn('sub_order_id');
        });
    }
};