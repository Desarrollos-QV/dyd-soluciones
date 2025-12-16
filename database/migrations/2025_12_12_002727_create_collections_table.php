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
           
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('unidad_id');

            $table->date('due_date');     // fecha de cobro
            $table->decimal('amount', 10, 2);

            $table->enum('status', [
                'pending',
                'notified',
                'paid',
                'overdue'
            ])->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('notified_at')->nullable();

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
        Schema::dropIfExists('collections');
    }
};
