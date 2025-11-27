<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Necesario para DB::statement

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agrega 'cerrado' a la lista de valores permitidos en el ENUM de la columna 'status'.
        // Los estados anteriores eran: 'pendiente', 'entregado', 'cancelado'.
        DB::statement("ALTER TABLE orders CHANGE status status ENUM('pendiente', 'entregado', 'cancelado', 'cerrado') NOT NULL DEFAULT 'pendiente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Si reviertes la migración, se remueve 'cerrado', volviendo a los estados originales.
        DB::statement("ALTER TABLE orders CHANGE status status ENUM('pendiente', 'entregado', 'cancelado') NOT NULL DEFAULT 'pendiente'");
    }
};