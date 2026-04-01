<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'no_telp', 'alamat', 'nip',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    // ── Relasi ──────────────────────────────────
    public function anggota()
    {
        return $this->hasOne(Anggota::class);
    }

    // ── Role Helpers ─────────────────────────────
    public function isAnggota(): bool        { return $this->role === 'anggota'; }
    public function isPetugas(): bool        { return $this->role === 'petugas'; }
    public function isKepala(): bool         { return $this->role === 'kep_perpustakaan'; }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'anggota'          => 'Anggota',
            'petugas'          => 'Petugas',
            'kep_perpustakaan' => 'Kepala Perpustakaan',
            default            => ucfirst($this->role),
        };
    }
}
