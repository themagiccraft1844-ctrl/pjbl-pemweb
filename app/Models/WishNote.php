<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishNote extends Model
{
    use HasFactory;

    // Pastikan fillable sesuai dengan migrasi wish_notes
    protected $fillable = [
        'id', 
        'judul', 
        'deskripsi_singkat',  
        'tipe_wadah',    // 'pohon', 'mading', 'mailbox'
        'privasi', // 'public', 'private'
        'like_count',
        'users_id'
    ];

    // Relasi ke User (Pemilik WishNote)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // PERBAIKAN DI SINI:
    // Ganti TreeMessage::class menjadi Message::class
    public function messages()
    {
        return $this->hasMany(Message::class, 'wish_note_id'); // Pastikan foreign key sesuai ('wishnote_id')
    }
}