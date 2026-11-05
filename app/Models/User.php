<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'no_whatsapp', 'alamat', 'foto'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class, 'pelapor_id');
    }

    public function dashboardRoute(): string
    {
        return $this->role === 'admin' ? 'admin.dashboard' : 'warga.dashboard';
    }
}
