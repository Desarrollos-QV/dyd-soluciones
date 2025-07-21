<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('direccion');
            $table->string('numero_contacto', 20);
            $table->string('numero_alterno', 20)->nullable();
            $table->string('pertenece_ruta')->nullable();
            $table->decimal('pago_mensual', 10, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_vencimiento');
            $table->enum('recordatorio', ['sms', 'email', 'whatsapp']);
            $table->text('mensaje_personalizado')->nullable();
            $table->text('mensaje_general')->nullable();
            $table->decimal('costo_plataforma', 10, 2)->nullable();
            $table->decimal('costo_sim', 10, 2)->nullable();
            $table->decimal('descuento', 10, 2)->nullable();
            $table->decimal('ganancia', 10, 2)->nullable();
            $table->enum('cobro_adicional', ['mensual', 'quincenal', 'semanal'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
