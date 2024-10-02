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
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->json('list_Absence');
            $table->unsignedBigInteger('seance_id');
            $table->foreign('seance_id')->references('id')->on('seances');
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->timestamps();
        });
    }
    // $table->id();
    // $table->unsignedBigInteger('student_id');
    // $table->foreign('student_id')->references('id')->on('students');
    // $table->unsignedBigInteger('seance_id');
    // $table->foreign('seance_id')->references('id')->on('seances');
    // $table->timestamps();

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
