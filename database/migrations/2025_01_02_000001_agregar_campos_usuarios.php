<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Usuarios
 * Fase:        1 - Base de Datos
 * Descripción: Agrega campos adicionales a la tabla
 *              users para roles, contacto y estado.
 * ═══════════════════════════════════════════════════
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'cajero'])->default('cajero')->after('email');
            $table->string('telefono', 20)->nullable()->after('role');
            $table->string('direccion')->nullable()->after('telefono');
            $table->string('foto')->nullable()->after('direccion');
            $table->boolean('is_active')->default(true)->after('foto');
            $table->timestamp('ultimo_acceso')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'telefono', 'direccion', 'foto', 'is_active', 'ultimo_acceso']);
        });
    }
};
