<?php

namespace App\Models;
use App\Models\WishNote; 

use Illuminate\Database\Eloquent\Model;

class TreeMessage extends Model
{
    protected $table = 'tree_messages';

    protected $fillable = [
        'tree_id',
        'user_id',
        'name',
        'message',
        'pesan',
        'color',
        'x',
        'y'
    ];

    public function tree()
    {
        return $this->belongsTo(WishNote::class, 'tree_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

