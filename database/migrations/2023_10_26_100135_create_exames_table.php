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
        Schema::create('exames', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('matier_id');
            $table->unsignedBigInteger('locale_id');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('matier_id')->references('id')->on('matieres');
            $table->foreign('locale_id')->references('id')->on('locales');
            $table->foreign('teacher_id')->references('id')->on('teachers');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exames');
    }
};
