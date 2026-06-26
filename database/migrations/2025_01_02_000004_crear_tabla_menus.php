<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Menús de Navegación
 * Fase:        1 - Base de Datos
 * Descripción: Menú de navegación configurable desde
 *              el panel admin. Soporta submenús
 *              mediante padre_id (autorreferencial).
 * ═══════════════════════════════════════════════════
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('enlace')->nullable();
            $table->string('icono')->nullable();
            $table->enum('ubicacion', ['principal', 'footer', 'sidebar'])->default('principal');
            $table->foreignId('padre_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->integer('orden')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
