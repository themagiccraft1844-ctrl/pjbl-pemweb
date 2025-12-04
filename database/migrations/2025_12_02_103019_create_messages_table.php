<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel WishNote (Wadah Utama)
            // Pastikan tabel wish_notes sudah dibuat di migrasi sebelumnya (timestamp lebih kecil)
            $table->foreignId('wish_note_id')->constrained('wish_notes')->onDelete('cascade');
            
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Data Konten
            $table->string('name')->default('Anonim'); // Nama Pengirim
            $table->text('message'); // Isi Pesan
            
            // Data Visual (Untuk Pohon Natal / Stiker Mading)
            $table->string('color')->nullable(); // Warna Bola/Kertas
            $table->string('x')->nullable(); // Koordinat X
            $table->string('y')->nullable(); // Koordinat Y
            
            $table->enum('visibility', ['public', 'private'])->default('public');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};