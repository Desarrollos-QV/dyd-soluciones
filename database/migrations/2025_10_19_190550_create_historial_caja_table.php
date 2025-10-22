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
        Schema::create('historial_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Usuario que registró
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->enum('tipo', ['ingreso', 'egreso']); // Tipo de movimiento
            $table->string('concepto');
            $table->decimal('monto', 12, 2);
            $table->string('metodo_pago')->nullable(); // efectivo, transferencia, etc
            $table->text('descripcion')->nullable();
            $table->string('autorizado_por')->nullable();
            $table->string('referencia')->nullable(); // folio o nota
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
        Schema::dropIfExists('historial_caja');
    }
};
