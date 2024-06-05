<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Kelas TI 4B</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: teal">

    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <div>
                            <h1 class="text-center my-4">Data Kelas TI 4B</h1>
                        </div>
                        @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ $value }}
                            </div>
                        @endsession

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('datakelas4b.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <a href="{{ route('datakelas4b.create') }}" class="btn btn-md btn-success mb-3">ADD DATA</a>
                            <button class="btn btn-md btn-success mb-3 float-end"><i class="fa fa-file"></i>IMPORT EXCEL</button>
                            <a href="{{ route('datakelas4b.export') }}" class="btn btn-md btn-warning mb-3 float-end"><i
                                    class="fa fa-download"></i>EXPORT EXCEL</a>
                            <a href="{{ ('printpdf') }}" class="btn btn-primary mb-3 float-end btn-print-pdf">Print PDF</a>
                            <input type="file" name="file" class="form-control" >
                        </form>
                        <table class="table table-bordered">
                            <thead>
                                <tr align="center">
                                    <th scope="col">No</th>
                                    <th scope="col">NIM</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Angkatan</th>
                                    <th scope="col">Jurusan</th>
                                    <th scope="col" style="width: 20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datakelas4b as $data_kelas)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data_kelas->nim }}</td>
                                        <td>{{ $data_kelas->nama }}</td>
                                        <td align="center">{{ $data_kelas->kelas }}</td>
                                        <td align="center">{{ $data_kelas->angkatan }}</td>
                                        <td>{{ $data_kelas->jurusan }}</td>
                                        <td class="text-center">
                                            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                action="{{ route('datakelas4b.destroy', $data_kelas->id) }}" method="POST">
                                                <a href="{{ route('datakelas4b.show', $data_kelas->id) }}"
                                                    class="btn btn-sm btn-dark">SHOW</a>
                                                <a href="{{ route('datakelas4b.edit', $data_kelas->id) }}"
                                                    class="btn btn-sm btn-primary">EDIT</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Tidak Ada Data.
                                    </div>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $datakelas4b->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        //message with sweetalert
        @if (session('success'))
            < div class="alert alert-success" >
                {{ session('success') }}
            </div >
        @endif
        @if (session('error'))
            < div class="alert alert-danger" >
                {{ session('error') }}
            </div >
        @endif
    </script>

</body>

</html>