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
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->foreignId('tecnico_id')->constrained('users')->nullable();
            $table->foreignId('unidad_id')->constrained('unidades');
            $table->decimal('pago_mensual', 10, 2);
            $table->date('fecha_inicio'); 
            $table->date('ultima_fecha_pago')->nullable();
            $table->decimal('costo_plataforma', 10, 2)->nullable();
            $table->decimal('costo_sim', 10, 2)->nullable();
            $table->decimal('descuento', 10, 2)->nullable();
            $table->decimal('ganancia', 10, 2)->nullable();
            $table->enum('cobro_adicional', ['mensual', 'quincenal', 'semanal'])->nullable();
            $table->date('fecha_ultimo_mantenimiento')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('observaciones_mantenimiento')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignaciones');
    }
};
