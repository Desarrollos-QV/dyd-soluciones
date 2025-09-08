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
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('name_company')->nullable();
            $table->string('name_prospect')->nullable();
            $table->string('company')->nullable();
            $table->enum('potencial' ,['bajo','medio','alto']);
            $table->unsignedBigInteger('sellers_id');
            $table->string('location')->nullable();
            $table->string('contacts')->nullable();
            $table->string('observations')->nullable();
            $table->timestamps();

            $table->foreign('sellers_id')->references('id')->on('sellers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prospects');
    }
};
