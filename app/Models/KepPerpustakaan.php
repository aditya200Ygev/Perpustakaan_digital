<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepPerpustakaan extends Model
{
    protected $table = 'kep_perpustakaan';
    protected $primaryKey = 'id_kep_perpustakaan';
    public $incrementing = true;
    protected $fillable = [
        'nm_kep_perpustakaan',
        'nip',
        'email',
        'no_telp',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_kep_perpustakaan', 'id_kep_perpustakaan');
    }
}
