²<?php

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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('CIN')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            // $table->string('promotion');
            // $table->string('amount');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Clés étrangères pour les relations parent et level
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
