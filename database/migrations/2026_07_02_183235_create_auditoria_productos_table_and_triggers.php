<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Crear tabla de auditoría
        Schema::create('auditoria_productos', function (Blueprint $table) {
            $table->id();
            $table->string('accion'); // INSERT, UPDATE, DELETE
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->string('nombre_producto')->nullable();
            
            $table->decimal('precio_viejo', 10, 2)->nullable();
            $table->decimal('precio_nuevo', 10, 2)->nullable();
            
            $table->decimal('costo_viejo', 10, 2)->nullable();
            $table->decimal('costo_nuevo', 10, 2)->nullable();
            
            $table->integer('stock_viejo')->nullable();
            $table->integer('stock_nuevo')->nullable();
            
            $table->string('usuario_db')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 2. Crear Triggers Nativos
        DB::unprepared("
            CREATE TRIGGER trg_productos_after_insert
            AFTER INSERT ON productos
            FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_productos 
                (accion, producto_id, nombre_producto, precio_nuevo, costo_nuevo, stock_nuevo, usuario_db)
                VALUES 
                ('INSERT', NEW.id, NEW.nombre, NEW.precio, NEW.costo, NEW.stock, CURRENT_USER());
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER trg_productos_after_update
            AFTER UPDATE ON productos
            FOR EACH ROW
            BEGIN
                IF OLD.precio != NEW.precio OR OLD.costo != NEW.costo OR OLD.stock != NEW.stock OR OLD.nombre != NEW.nombre THEN
                    INSERT INTO auditoria_productos 
                    (accion, producto_id, nombre_producto, precio_viejo, precio_nuevo, costo_viejo, costo_nuevo, stock_viejo, stock_nuevo, usuario_db)
                    VALUES 
                    ('UPDATE', NEW.id, NEW.nombre, OLD.precio, NEW.precio, OLD.costo, NEW.costo, OLD.stock, NEW.stock, CURRENT_USER());
                END IF;
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER trg_productos_after_delete
            AFTER DELETE ON productos
            FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_productos 
                (accion, producto_id, nombre_producto, precio_viejo, costo_viejo, stock_viejo, usuario_db)
                VALUES 
                ('DELETE', OLD.id, OLD.nombre, OLD.precio, OLD.costo, OLD.stock, CURRENT_USER());
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_productos_after_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_productos_after_update");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_productos_after_delete");
        
        Schema::dropIfExists('auditoria_productos');
    }
};
