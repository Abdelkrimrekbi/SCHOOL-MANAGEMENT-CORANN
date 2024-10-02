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
        Schema::create('teacher_has_matiers', function (Blueprint $table) {
            $table->id();
            $table->string('promotion');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('matier_id');
            $table->timestamps();

            // Clés étrangères pour les relations parent et level
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('matier_id')->references('id')->on('matieres');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_has_matiers');
    }
};
