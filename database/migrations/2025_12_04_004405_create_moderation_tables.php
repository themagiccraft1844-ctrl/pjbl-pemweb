<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Tabel Email Banned (Blacklist)
        Schema::create('banned_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->text('reason')->nullable(); // Alasan banned
            $table->string('admin_name')->nullable(); // Siapa yang nge-ban
            $table->timestamps();
        });

        // 2. Tabel Alerts (Riwayat Teguran)
        Schema::create('user_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('level', ['ringan', 'menengah', 'berat']);
            $table->text('pesan'); // Pesan dari admin kenapa ditegur
            $table->timestamps();
        });

        // 3. Update Users (Kolom Suspend)
        Schema::table('users', function (Blueprint $table) {
            // null = aman, terisi tanggal = disuspend sampai tanggal tersebut
            $table->timestamp('suspended_until')->nullable(); 
            // tipe suspend: 'none', 'light' (cuma gak bisa nulis), 'medium' (gak bisa login)
            $table->enum('suspension_type', ['none', 'light', 'medium'])->default('none');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_tables');
    }
};
