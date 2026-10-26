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
        \DB::statement("SET SESSION sql_mode = ''");
        \DB::statement('ALTER TABLE clientes ADD identificacion_trasera VARCHAR(255) NULL AFTER identificacion');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE clientes DROP COLUMN identificacion_trasera');
    }
};
