<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; // Pastikan Model Buku sudah ada

class HomeController extends Controller
{
   public function index()
{
    // Ambil buku slider (sudah ada sebelumnya)
    $slider_buku = Buku::latest()->take(4)->get();

    // Ambil buku galeri (kategori id 5)
    $galeri_buku = Buku::where('kategori_id', 5)->latest()->get();

    // BARU: Ambil buku untuk bagian Kategori Utama (ID 6)
    // Kita ambil 5 buku (1 untuk highlight kiri, 4 untuk grid kanan)
    $kategori_utama = Buku::where('kategori_id', 6)->latest()->take(5)->get();

    return view('home', compact('slider_buku', 'galeri_buku', 'kategori_utama'));
}
}
