<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $primaryKey = 'id_pengembalian';
    public $incrementing = true;
    protected $fillable = [
        'tgl_kembali',
        'status_pengembalian',
        'id_pinjam',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_pinjam', 'id_pinjam');
    }

    public function denda()
    {
        return $this->hasOne(Denda::class, 'id_pengembalian', 'id_pengembalian');
    }
}
