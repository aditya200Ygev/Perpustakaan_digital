<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $primaryKey = 'id_pengembalian';
    public $incrementing = true;

    protected $fillable = [
        'id_pinjam',
        'tgl_kembali',
        'status_pengembalian',  // ✅ nama kolom yang valid
        'keterangan',           // opsional
        'petugas_id',           // opsional: siapa yang ACC
    ];

    protected $casts = [
        'tgl_kembali' => 'datetime',
    ];

    public function peminjaman()
    {
        // Asumsi: peminjamans.primary_key = 'id' (default Laravel)
        return $this->belongsTo(Peminjaman::class, 'id_pinjam', 'id');
    }

    public function denda()
    {
        return $this->hasOne(Denda::class, 'id_pengembalian', 'id_pengembalian');
    }
}
