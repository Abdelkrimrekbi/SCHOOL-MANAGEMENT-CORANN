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
        Schema::create('filieres', function (Blueprint $table) {
            $table->id();
            $table->string('filiere');
          //  $table->unsignedBigInteger('departement_id');
            $table->unsignedBigInteger('level_id');
            $table->timestamps();

            // Clés étrangères pour les relations parent et level
         //   $table->foreign('departement_id')->references('id')->on('departements');
            $table->foreign('level_id')->references('id')->on('levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
