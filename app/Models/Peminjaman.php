<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_pinjam';
    public $incrementing = true;
    protected $fillable = [
        'tgl_pinjam',
        'tgl_kembali',
        'id_user',
        'id_buku',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_pinjam', 'id_pinjam');
    }

    public function laporan()
    {
        return $this->hasOne(Laporan::class, 'id_pinjam', 'id_pinjam');
    }
}
