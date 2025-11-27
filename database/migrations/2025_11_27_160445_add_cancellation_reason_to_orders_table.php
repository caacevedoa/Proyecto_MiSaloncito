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
        Schema::table('orders', function (Blueprint $table) {
            // Agrega la columna para el motivo de cancelación
            // Usamos 'text' para permitir motivos largos y 'nullable' porque es opcional.
            $table->text('cancellation_reason')->nullable()->after('total'); // Se agrega después de 'total'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Elimina la columna si se revierte la migración
            $table->dropColumn('cancellation_reason');
        });
    }
};