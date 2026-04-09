<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'keuangans';
    protected $fillable = [
    'peminjaman_id',
        'total_denda',
        'tanggal'
    ];
    public function peminjaman()
{
    return $this->belongsTo(\App\Models\Peminjaman::class);
}public function user() {
    return $this->belongsTo(User::class);
}

public function buku() {
    return $this->belongsTo(Buku::class);
}
}
