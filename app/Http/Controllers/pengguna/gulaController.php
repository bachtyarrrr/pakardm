<?php

namespace App\Http\Controllers\pengguna;

use App\Http\Controllers\pengguna\DashboardController;
use App\Models\Pesan;
use App\Http\Requests\pengguna\PesanRequest;

class PesanController extends PenggunaController
{
    public $title = 'Cek Gula Darah';
    public function index()
    {
        $title = $this->title;
        $bcrum = $this->bcrum('Gula');
        return view('pengguna.gula.create', compact('title', 'bcrum'));
    }

    // public function store(PesanRequest $request)
    // {
    //     $data = $request->all();
    //     Pesan::create($data);
    //     $this->notification('success', 'Berhasil', 'Pesan Berhasil Terkirim');
    //     return redirect(route('pengguna.pesan.index'));
    // }
}
