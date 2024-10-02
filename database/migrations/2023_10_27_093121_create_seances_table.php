<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('teacherHasMatier_id');
          
            $table->timestamps();

            // Clés étrangères pour les relations parent et level
            $table->foreign('teacherHasMatier_id')->references('id')->on('teacher_has_matiers');
         //   $table->foreign('local_id')->references('id')->on('locales');
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};
