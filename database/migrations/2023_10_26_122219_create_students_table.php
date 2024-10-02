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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date_birth');
            $table->string('lieu_birth');
            $table->string('address');
            $table->integer('niveau_etude')->nullable();
            $table->string('promotion');
            $table->unsignedBigInteger('level_id');
            $table->string('phone')->nullable();
            $table->string('profession')->nullable();
            $table->string('la_situation')->nullable();
            $table->string('name_school')->nullable();
            $table->boolean('isChecked')->default(false);
            $table->unsignedBigInteger('class_id')->nullable();
            $table->string('droit_annuel')->nullable();
            $table->string('service_mensuel')->nullable();
            
          
            $table->timestamps();
       
            $table->foreign('class_id')->references('id')->on('groups');
            $table->foreign('level_id')->references('id')->on('levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
