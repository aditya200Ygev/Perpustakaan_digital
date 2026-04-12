<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Buku extends Model
{
   protected $fillable = [
    'judul',
    'penulis',
    'penerbit',
    'tahun_terbit',
    'stok',
    'cover',
    'kategori_id',
    'deskripsi'
];

public function kategori()
{
    return $this->belongsTo(Kategori::class);
}
  public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}
