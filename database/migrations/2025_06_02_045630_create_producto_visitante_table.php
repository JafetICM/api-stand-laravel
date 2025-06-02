<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('producto_visitante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visitante_id');
            $table->unsignedBigInteger('producto_id');

            $table->foreign('visitante_id')->references('id')->on('visitantes')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_visitante');
    }
};
