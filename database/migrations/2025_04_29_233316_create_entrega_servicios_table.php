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
        Schema::create('entrega_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ejecucion_instalacion_id')->constrained()->cascadeOnDelete();
            $table->string('firma_cliente'); // ruta firma digital
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
        Schema::dropIfExists('entrega_servicios');
    }
};
