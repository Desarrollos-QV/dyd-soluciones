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
        Schema::create('servicios_agendados', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_servicio', ['Instalación', 'Reparación', 'Apoyo']);
            $table->date('fecha');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('titular');
            $table->string('contacto');
            $table->string('unidad');
            $table->text('falla_reportada')->nullable();
            $table->text('reparacion_realizada')->nullable();
            // Refacciones (json)
            $table->json('refacciones')->nullable();
            $table->json('refacciones_cantidad')->nullable();
            // Fotos
            $table->json('fotos')->nullable();
            $table->string('firma_cliente')->nullable();
            // administración
            $table->decimal('costo_instalador', 15,2)->default(0);
            $table->decimal('gasto_adicional', 15,2)->default(0);
            $table->decimal('saldo_favor', 15,2)->default(0);
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
        Schema::dropIfExists('servicios_agendados');
    }
};
