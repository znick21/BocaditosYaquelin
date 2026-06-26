<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Configuración del Sistema
 * Fase:        1 - Base de Datos
 * Descripción: Tabla de configuración general del
 *              negocio. Almacena nombre, logo, datos
 *              de contacto, colores y parámetros.
 *              Es una tabla de fila única.
 * ═══════════════════════════════════════════════════
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id();

            // ── Identidad del negocio ──
            $table->string('nombre_negocio')->default('Bocaditos Yaquelin');
            $table->string('eslogan')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            // ── Datos de contacto ──
            $table->string('telefono', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();

            // ── Redes sociales ──
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();

            // ── Parámetros del negocio ──
            $table->string('moneda', 10)->default('Bs');
            $table->decimal('impuesto_porcentaje', 5, 2)->default(0);
            $table->string('horario_apertura', 10)->nullable();
            $table->string('horario_cierre', 10)->nullable();

            // ── Personalización visual ──
            $table->string('color_primario', 7)->default('#f59e0b');
            $table->string('color_secundario', 7)->default('#ea580c');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};
