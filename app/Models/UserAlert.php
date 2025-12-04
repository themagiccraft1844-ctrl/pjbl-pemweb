<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAlert extends Model {
    protected $fillable = ['user_id', 'level', 'pesan'];
}