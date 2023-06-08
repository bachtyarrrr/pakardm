@extends('layouts.admin.main')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <section class="section">
        {{-- Header --}}
        <div class="section-header">
            <h1>{{ $title }}</h1>
        </div>
        {{-- Body --}}
        <div class="section-body">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Daftar {{ $title }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tabel">
                                <thead>
                                    <tr>
                                        <th width="8%">No</th>
                                        <th>Nama Gejala</th>
                                        <th>Akar</th>
                                        <th>Keputusan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bps as $bp)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ Str::title($bp->gejala->nama) }}</td>
                                            <td>{{ Str::title($bp->gejala->kategori) }}</td>
                                            <td>{{ Str::title($bp->penyakit->nama) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('assets/vendor/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        function showLoadingSpinner() {
            document.querySelector('.spinner-border').classList.remove('d-none')
            document.querySelector('.spinner-border').classList.add('d-block')
            
            setTimeout(() => {
                document.querySelector('.spinner-border').classList.remove('d-block')
                document.querySelector('.spinner-border').classList.add('d-none')

                Swal.fire({
                    title: 'Data Mining Berhasil!',
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                })
            }, 7000);
        }
    </script>


    <script>
        // Datatables
        $(document).ready(() => {
            $('#tabel').DataTable({
                language: {
                    search: 'Cari',
                    searchPlaceholder: 'Cari pohon keputusan'
                }
            });
        });

            $('.btn-hapus').click(function() {
                let id = $(this).val();
                Swal.fire({
                    title: 'Perhatian!',
                    text: "Apakah Anda Yakin Untuk Menghapus Data?",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        hapusData(id);
                    }
                })
            })

            function hapusData(id) {
                let url = $(`#delete_${id}`).attr('action');
                let data = $(`#delete_${id}`).serialize();
                let method = 'POST';
                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response) {
                        Swal.fire(
                            'Berhasil!',
                            'Data Basis Pengetahuan Berhasil Dihapus',
                            'success'
                        )
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                })

            }

    </script>

@endpush
