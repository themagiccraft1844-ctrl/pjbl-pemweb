<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishNote extends Model
{
    // jika perlu, tambahkan fillable / table / timestamps sesuai kebutuhan
    protected $fillable = [
        'judul',
        'deskripsi_singkat',
        'tipe_wadah',
        'like_count',
        'privasi',
        'users_id',
    ];

    // Relasi ke user (sudah pernah disarankan)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }

    // === Tambahkan ini: relasi messages ===
    public function messages()
    {
        // Ganti 'Message::class' atau foreign key jika berbeda
        return $this->hasMany(\App\Models\TreeMessage::class,'tree_id', 'id');
    }
    protected static function booted()
    {
    static::deleting(function ($wishnote) {
        $wishnote->messages()->delete();
    });
    }
}
