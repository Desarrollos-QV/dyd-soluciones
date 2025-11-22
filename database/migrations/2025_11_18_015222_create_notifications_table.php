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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Para quién va dirigida
            $table->unsignedBigInteger('user_id')->nullable();  // Admin, técnico 

            // Tipo de notificación
            $table->string('type'); // service_assigned, payment_pending, mensualidad_vencida, etc.

            // Cuerpo de la notificación
            $table->string('title');
            $table->text('message');

            // Datos adicionales
            $table->json('data')->nullable(); // id del servicio, monto, fecha, etc.

            // Estado
            $table->boolean('is_read')->default(false);

            // Ruta para redireccionar al hacer clic en la notificación
            $table->string('route_redirect')->nullable();
            // Opcional: fecha límite
            $table->datetime('expires_at')->nullable();

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
        Schema::dropIfExists('notifications');
    }
};
