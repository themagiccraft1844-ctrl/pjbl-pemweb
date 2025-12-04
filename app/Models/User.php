<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'suspended_until',
        'suspension_type'
    ];
    
    protected $casts = [
        'suspended_until' => 'datetime',
    ];

    // Cek apakah user sedang disuspend?
    public function isSuspended() {
        return $this->suspended_until && $this->suspended_until->isFuture();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke teman (dimana user ini sebagai PENGIRIM request)
    public function friendsOfMine()
    {
        return $this->belongsToMany(User::class, 'friendships', 'sender_id', 'receiver_id')
            ->wherePivot('status', 'accepted');
    }

    // Relasi ke teman (dimana user ini sebagai PENERIMA request)
    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friendships', 'receiver_id', 'sender_id')
            ->wherePivot('status', 'accepted');
    }

    // Gabungkan keduanya untuk dapat SEMUA teman
    public function getFriendsAttribute()
    {
        return $this->friendsOfMine->merge($this->friendOf);
    }

    public function wishnotes()
    {
        return $this->hasMany(WishNote::class, 'users_id');
    }
}