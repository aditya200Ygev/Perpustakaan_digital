<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    protected $fillable = [
        'id_pinjam',
        'user_id',
        'buku_id',
        'tgl_pinjam',
        'jumlah',
        'tgl_kembali',
        'status',
    'denda',
    'is_paid'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function keuangan()
    {
        // Parameter kedua adalah foreign key di tabel keuangans (misal: peminjaman_id)
        return $this->hasOne(Keuangan::class, 'peminjaman_id');
    }
}
