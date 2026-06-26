<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Detalle de Ventas
 * Fase:        1 - Base de Datos
 * Descripción: Líneas individuales de cada venta.
 *              Guarda snapshot del nombre y precio
 *              del producto al momento de la venta
 *              para integridad histórica (3FN
 *              desnormalización intencional auditora).
 * ═══════════════════════════════════════════════════
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');

            // ── Snapshot: datos al momento de la venta ──
            // Se almacenan para preservar la integridad histórica.
            // Si el precio o nombre del producto cambia después,
            // las ventas anteriores conservan el dato original.
            $table->string('nombre_producto');
            $table->decimal('precio_unitario', 10, 2);

            $table->integer('cantidad');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
