<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();

            // Kolom untuk penerima tugas (User)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Kolom untuk pembuat tugas (Admin)
            // Relasi ke tabel users, tapi dengan nama kolom berbeda
            $table->foreignId('assigned_by_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('title');
            $table->text('description')->nullable();

            // Deadline
            $table->timestamp('deadline')->nullable();

            // Jika null = belum selesai. Jika terisi = waktu selesai.
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
