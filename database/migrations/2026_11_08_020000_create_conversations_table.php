<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            // Pengikat sementara untuk demo sebelum login sungguhan dibuat.
            $table->string('owner_session', 128)->index();

            // Nomor tiket internal; judul yang dilihat pengguna disimpan terpisah.
            $table->string('ticket', 20)->unique();
            $table->string('title', 80)->default('Obrolan baru');

            $table->unsignedTinyInteger('mood')->nullable();
            $table->string('topic', 30)->nullable();
            $table->string('lang', 2)->default('id');
            $table->string('status', 20)->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};