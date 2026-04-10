<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    const STATUS_DIAJUKAN = 'diajukan';
    const STATUS_DIPINJAM = 'dipinjam';
    const STATUS_PENGAJUAN_KEMBALI = 'pengembalian_diajukan';
    const STATUS_DENDA = 'denda';
    const STATUS_SELESAI = 'selesai';

    // Cara pakai:
    // $pinjam->status = Peminjaman::STATUS_PENGAJUAN_KEMBALI;
    // Jika id_pinjam BUKAN primary key, tambahkan:
    // protected $primaryKey = 'id';
    // public $incrementing = true;

    protected $fillable = [
        'id_pinjam',      // custom ID string, misal: 'PJ123456'
        'user_id',
        'buku_id',
        'tgl_pinjam',
        'jumlah',
        'tgl_kembali',
        'status',
        'denda',        // 'diajukan', 'dipinjam', 'pengembalian_diajukan', 'denda', 'selesai'
        'is_denda',       // ✅ boolean: true jika telat
        'is_paid'         // ✅ boolean: true jika denda sudah dibayar
    ];

    // Opsional: casting untuk boolean
    protected $casts = [
        'is_denda' => 'boolean',
        'is_paid'  => 'boolean',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function keuangan()
    {
        return $this->hasOne(Keuangan::class, 'peminjaman_id');
    }




}
