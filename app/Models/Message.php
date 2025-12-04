<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'wish_note_id', // Foreign Key
        'user_id',
        'name',
        'message',
        'color',
        'x',
        'y',
        'visibility'
    ];

    // Relasi balik ke WishNote
    public function wishNote()
    {
        return $this->belongsTo(WishNote::class, 'wish_note_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}