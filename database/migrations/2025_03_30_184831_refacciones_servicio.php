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
        Schema::create('refacciones_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios_agendados')->onDelete('cascade');
            $table->enum('refaccion', ['Modulo 4G', 'Antena 4G', 'Antena GPS', 'Tarjeta de Video', 'Modulo GPS', 'SIM', 'Arnés Alarmas', 'Arnés Corriente', 'Relevador', 'Base de Relevador']);
            $table->integer('cantidad');
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
        Schema::dropIfExists('refacciones_servicio');
    }
};
