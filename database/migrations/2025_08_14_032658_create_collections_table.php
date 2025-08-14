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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('recordatorios',['sms','whatsapp','email'])->default('sms');
            $table->string('mensaje_personalizado')->nullable();
            $table->enum('mensajes_automaticos' ,['si','no'])->default('si');
            $table->integer('dias_tolerancia')->nullable();
            $table->string('TWILIO_SID')->nullable();
            $table->string('TWILIO_AUTH_TOKEN')->nullable();
            $table->string('TWILIO_PHONE')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections');
    }
};
