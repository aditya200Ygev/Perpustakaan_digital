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
        'tgl_kembali',
        'status'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
