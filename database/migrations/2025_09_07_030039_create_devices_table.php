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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('dispositivo')->nullable();
            $table->string('marca')->nullable();
            $table->string('camaras')->nullable();
            $table->string('generacion')->nullable();
            $table->string('imei')->nullable();
            $table->integer('garantia')->nullable();
            $table->string('accesorios')->nullable();
            $table->enum('ia', ['si','no'])->default('no');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('unidad_id')->nullable();
            $table->enum('otra_empresa',['si','no'])->default('no');
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('unidad_id')->references('id')->on('unidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
};
