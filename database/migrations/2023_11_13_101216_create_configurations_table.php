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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('logo');
            $table->string('email')->nullable();
            $table->string('site')->nullable();
            $table->string('local')->nullable();
            $table->string('RC')->nullable();
            $table->string('IF')->nullable();
            $table->string('CNSS')->nullable();
            $table->string('patente')->nullable();
            $table->string('ICE')->nullable();
            $table->string('RIB')->nullable();
            $table->string('promotion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
