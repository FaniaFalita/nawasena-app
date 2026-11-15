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
        Schema::create('counseling_sessions', function (Blueprint $table) { // <-- Ubah di sini
            $table->id();
            $table->string('student_name')->nullable(); 
            $table->string('student_email')->nullable(); 
            $table->text('story'); 
            $table->string('language')->default('id'); 
            $table->string('theme_preference')->default('light'); 
            $table->string('urgency_level')->default('pending'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
