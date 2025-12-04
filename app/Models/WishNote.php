<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id', // Kolom di database (Foreign Key)
        'judul',
        'deskripsi_singkat',
        'tipe_wadah',
        'privasi',
        'like_count'
    ];

    /**
     * Relasi ke User (Pemilik)
     */
    public function user()
    {
        // PERBAIKAN: Tambahkan parameter kedua 'users_id'
        // Ini memberitahu Laravel: "Foreign Key di tabel ini bernama 'users_id', bukan 'user_id'"
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Relasi ke Pesan (Isi WishNote)
     */
    public function messages()
    {
        // Pastikan parameter kedua sesuai dengan kolom di tabel messages ('wish_note_id')
        return $this->hasMany(Message::class, 'wish_note_id');
    }
}