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
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('comprobante_domicilio')->after('identificacion');
            $table->string('copa_factura')->after('comprobante_domicilio');
            $table->string('tarjeta_circulacion')->after('copa_factura');
            $table->string('copia_consesion')->after('tarjeta_circulacion');
            $table->string('contrato')->after('copia_consesion');
            $table->string('anexo')->after('contrato');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            //
        });
    }
};
