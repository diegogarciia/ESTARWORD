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
        Schema::create('naves_estelares', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('modelo')->nullable();
            $table->string('tripulacion')->nullable();
            $table->string('pasajeros')->nullable();
            $table->string('clase_nave')->nullable();
            $table->foreignId('id_planeta');
            $table->foreignId('id_piloto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naves_estelares');
    }
};
