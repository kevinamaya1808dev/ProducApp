<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * - proveedores.tipo: 'empresa' | 'particular' (los existentes quedan como 'empresa').
 * - materials.proveedor_id: enlace opcional a un proveedor registrado.
 *   La columna de texto materials.proveedor se conserva y pasa a ser el
 *   proveedor "temporal" (escrito a mano, que no está en el directorio).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->string('tipo', 20)->default('empresa')->after('nombre');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('proveedor_id')->nullable()->after('proveedor')
                ->constrained('proveedores')->nullOnDelete();
        });

        // Enlaza los materiales cuyo texto coincide con UN solo proveedor registrado
        // (sin distinguir mayúsculas). El texto original NO se borra: es no destructivo.
        $porNombre = DB::table('proveedores')->get()->groupBy(fn ($p) => mb_strtolower(trim($p->nombre)));

        DB::table('materials')->whereNotNull('proveedor')->where('proveedor', '<>', '')->orderBy('id')
            ->each(function ($material) use ($porNombre) {
                $coincidencias = $porNombre->get(mb_strtolower(trim($material->proveedor)));

                if ($coincidencias && $coincidencias->count() === 1) {
                    DB::table('materials')->where('id', $material->id)->update(['proveedor_id' => $coincidencias->first()->id]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropConstrainedForeignId('proveedor_id');
        });

        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
