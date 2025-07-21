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
        Schema::create('ejecucion_instalacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_instalacion_id')->constrained()->cascadeOnDelete();
            $table->string('foto_corriente')->nullable();
            $table->string('foto_apagado')->nullable();
            $table->string('foto_relevador')->nullable();
            $table->string('foto_tierra')->nullable();
            $table->string('foto_equipo')->nullable();
            $table->boolean('video_enviado')->default(false);
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('ejecucion_instalacions');
    }
};
