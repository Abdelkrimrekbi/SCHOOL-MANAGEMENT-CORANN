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
        if (!Schema::hasTable('guardians')) {
            Schema::create('guardians', function (Blueprint $table) {
                $table->id();
                $table->string('CIN')->unique();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('phone');
                $table->string('address');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();

                // Clés étrangères pour les relations parent et level
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {

        // Schema::table('students', function (Blueprint $table) {
        //     $table->dropForeign(['parent_id']);
        // });
        Schema::dropIfExists('guardians');
    }
};
