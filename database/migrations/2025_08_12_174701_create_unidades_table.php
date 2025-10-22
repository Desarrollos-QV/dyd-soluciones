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
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->text('tipo_unidad');
            $table->date('fecha_instalacion')->nullable();
            $table->text('dispositivo_instalado')->nullable(); 
            $table->string('economico')->nullable();
            $table->string('placa')->nullable();
            $table->integer('anio_unidad')->nullable();
            $table->string('vin')->nullable();
            $table->string('imei')->nullable();
            $table->string('sim_dvr')->nullable();
            $table->string('marca_submarca')->nullable();
            $table->string('numero_de_motor')->nullable();
            $table->string('usuario')->nullable();
            $table->string('password')->nullable();
            $table->enum('cuenta_con_apagado',['si','no'])->default('no')->nullable();
            $table->string('numero_de_emergencia')->nullable();
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
        Schema::dropIfExists('unidades');
    }
};
