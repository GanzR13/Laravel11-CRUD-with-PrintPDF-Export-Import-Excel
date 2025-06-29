<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Kelas TI 4B</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        #pagination-wrapper .d-sm-flex>div:first-child {
            display: none;
        }

        #pagination-wrapper .justify-content-sm-between {
            justify-content: center !important;
        }
    </style>
</head>

<body style="background: teal;">

    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <h1 class="text-center my-4">Data Mahasiswa Kelas TI 4B</h1>

                        <div class="d-flex justify-content-between mb-3">
                            <a href="{{ route('datakelas4b.create') }}" class="btn btn-md btn-success">
                                <i class="fa fa-plus"></i> TAMBAH DATA
                            </a>
                            <div>
                                <a href="{{ route('datakelas4b.export') }}" class="btn btn-md btn-warning">
                                    <i class="fa fa-download"></i> EXPORT EXCEL
                                </a>
                                <a href="{{ route('datakelas4b.printpdf') }}" class="btn btn-md btn-primary"
                                    target="_blank">
                                    <i class="fa fa-print"></i> PRINT PDF
                                </a>
                            </div>
                        </div>

                        {{-- Form untuk import data --}}
                        <div class="card bg-light mt-3 mb-4">
                            <div class="card-body">
                                <form action="{{ route('datakelas4b.import') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">

                                        <input type="file" name="file" class="form-control" required
                                            accept=".pdf,.xls,.xlsx,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">

                                        <button class="btn btn-info" type="submit">
                                            <i class="fa fa-upload"></i> IMPORT FILE
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr class="text-center">
                                        <th scope="col">No</th>
                                        <th scope="col">NIM</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Kelas</th>
                                        <th scope="col">Angkatan</th>
                                        <th scope="col">Jurusan</th>
                                        <th scope="col" style="width: 15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($datakelas4b as $data_kelas)
                                        <tr>

                                            <td class="text-center">
                                                {{ ($datakelas4b->currentPage() - 1) * $datakelas4b->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="text-center">{{ $data_kelas->nim }}</td>
                                            <td>{{ $data_kelas->nama }}</td>
                                            <td class="text-center">{{ $data_kelas->kelas }}</td>
                                            <td class="text-center">{{ $data_kelas->angkatan }}</td>
                                            <td>{{ $data_kelas->jurusan }}</td>
                                            <td class="text-center">
                                                <form class="form-delete d-inline-block"
                                                    action="{{ route('datakelas4b.destroy', $data_kelas->id) }}"
                                                    method="POST">

                                                    <button type="button" class="btn btn-sm btn-dark"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#showModal{{ $data_kelas->id }}">
                                                        <i class="fa fa-eye"></i>
                                                    </button>

                                                    <a href="{{ route('datakelas4b.edit', $data_kelas->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fa fa-pencil-alt"></i>
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        {{-- Struktur Modal untuk setiap data --}}
                                        <div class="modal fade" id="showModal{{ $data_kelas->id }}" tabindex="-1"
                                            aria-labelledby="showModalLabel{{ $data_kelas->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="showModalLabel{{ $data_kelas->id }}">Detail Data
                                                            Mahasiswa</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item"><b>NIM:</b>
                                                                {{ $data_kelas->nim }}</li>
                                                            <li class="list-group-item"><b>Nama:</b>
                                                                {{ $data_kelas->nama }}</li>
                                                            <li class="list-group-item"><b>Kelas:</b>
                                                                {{ $data_kelas->kelas }}</li>
                                                            <li class="list-group-item"><b>Angkatan:</b>
                                                                {{ $data_kelas->angkatan }}</li>
                                                            <li class="list-group-item"><b>Jurusan:</b>
                                                                {{ $data_kelas->jurusan ?? 'Tidak ada data' }}</li>
                                                            <li class="list-group-item"><b>Dibuat Pada:</b>
                                                                {{ $data_kelas->created_at->format('d M Y, H:i') }}
                                                            </li>
                                                            <li class="list-group-item"><b>Diubah Pada:</b>
                                                                {{ $data_kelas->updated_at->format('d M Y, H:i') }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="alert alert-danger my-2">
                                                    Tidak Ada Data.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4" id="pagination-wrapper">
                            <nav>
                                {{ $datakelas4b->links() }}
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Notifikasi flash message dengan SweetAlert2
        @if (session('success'))
            Swal.fire({
                icon: "success",
                title: "BERHASIL",
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @elseif (session('error'))
            Swal.fire({
                icon: "error",
                title: "GAGAL!",
                html: "{!! session('error') !!}",
                showConfirmButton: true,
            });
        @endif

        // Konfirmasi hapus data dengan SweetAlert2
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.form-delete');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    })
                });
            });
        });
    </script>

</body>

</html>
