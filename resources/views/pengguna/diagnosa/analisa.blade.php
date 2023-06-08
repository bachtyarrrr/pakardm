@extends('layouts.pengguna.main')

@section('content')
    <section class="inn">
        <div class="container">
            <div class="no-print">
                <button type="button" class="btn btn-primary" style="float: right" onclick="window.print()">Cetak Hasil Diagnosa</button>
            </div>
            <h2 class="text-center mb-2 fw-bold">Hasil Diagnosa</h2>
            <hr class="mb-4">
            <div class="pilihan" class="mt-4">
                <h3 style="font-size: 25px" class="mb-2">Pilihan Pengguna</h3>
                <table class="table table-bordered table-hovered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gejala</th>
                            <th>Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gejalas as $gejala)
                            @foreach ($kepastian as $key => $kp)
                                @if ($gejala->id == $key)
                                <tr>
                                    <td>{{$loop->iteration}}</td>

                                    <td>{{$gejala->nama}}</td>
                                    <td>
                                        @if($kp == 1)
                                        Ya
                                        @else
                                        Tidak
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="my-4"></div>
                <div class="row bg-light rounded-sm mt-4">
                    <div class="col-md-6 p-3">
                        <h3 style="font-size: 25px" class="mb-4">Hasil Diagnosa</h3>
                        <p>Berdasarkan daftar gejala yang dipilih, Penyakit pada tanaman padi anda adalah :</p>
                        @if ($penyakit == null)
                            <h4 style="font-size: 22px" class="mb-3 text-danger">Penyakit tidak ditemukan. Periksa kembali input atau basis pengetahuan anda.</h4>
                        @else
                            <h4 style="font-size: 22px" class="mb-3 text-success">{{ $penyakit->nama }}</h4>
                        @endif
                    </div>
                    @if ($penyakit != null)
                        <div class="col-md-6 d-flex justify-content-center p-3">
                            <img src="{{asset('assets/gambar/' . $penyakit->gambar)}}" alt="{{$penyakit->nama}}" width="400px" class="rounded-lg">
                        </div>
                    @endif
                </div>
                <div class="my-4"></div>
                @if ($penyakit != null)
                    <div class="card">
                        <div class="card-body">
                            <h3 style="font-size: 25px" class="mb-2">Deskripsi penyakit</h3>
                            <br>
                            {!!$penyakit->deskripsi!!}
                        </div>
                    </div>
                    <div class="my-4"></div>
                    <div class="card">
                        <div class="card-body">
                            <h3 style="font-size: 25px" class="mb-2">Solusi penyakit</h3>
                            <br>
                            {!!$penyakit->solusi!!}
                        </div>
                    </div>
                @endif
            <div class="my-4"></div>
            <div class="just-print">
                <p>* Hasil diagnosa dapat ditunjukan ke pakar terdekat</p></p>
            </div>
        </div>
    </section>
@endSection

@push('script')
    <script>
        $('#plain tr:first').hide();
    </script>
@endpush
