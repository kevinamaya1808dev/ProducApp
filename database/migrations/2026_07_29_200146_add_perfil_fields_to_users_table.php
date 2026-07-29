<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('puesto')->nullable()->after('name');
            $table->string('turno')->nullable()->after('puesto');
            $table->string('planta')->nullable()->after('turno');
            $table->boolean('active')->default(true)->after('planta');
            $table->unsignedInteger('meta_diaria')->nullable()->after('active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['puesto', 'turno', 'planta', 'active', 'meta_diaria']);
        });
    }
};