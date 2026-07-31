<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('components', function (Blueprint $table) {
            $table->foreignId('component_type_id')
                  ->nullable()
                  ->constrained('component_types')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('components', function (Blueprint $table) {
            $table->dropConstrainedForeignId('component_type_id');
        });
    }
};