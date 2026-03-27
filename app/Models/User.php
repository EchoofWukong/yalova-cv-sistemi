<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_approved',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    /**
     * E-posta doğrulaması kontrolü.
     * Akademisyenler için otomatik true döner, öğrenciler linke tıklamalıdır.
     */
    public function hasVerifiedEmail()
    {
        // Eğer rolü teacher (öğretmen) ise mail onayı isteme (zaten manuel onaylıyoruz)
        if ($this->role === 'teacher') {
            return true;
        }

        // Öğrenciyse normal e-posta onay tarihine bak
        return ! is_null($this->email_verified_at);
    }
}