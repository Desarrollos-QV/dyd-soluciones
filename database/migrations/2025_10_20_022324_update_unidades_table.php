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
        Schema::table('unidades', function (Blueprint $table) {
            $table->double('costo_plataforma')->nullable()->after('numero_de_motor');
            $table->double('costo_sim')->nullable()->after('costo_plataforma');
            $table->double('pago_mensual')->nullable()->after('costo_sim');
            $table->string('name_empresa')->nullable()->after('pago_mensual');
            $table->string('credenciales')->nullable()->after('name_empresa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
