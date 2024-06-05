<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: lightgray">

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <h3>{{ $data_kelas->nama }}</h3>
                        <hr />
                        <code>
                            <p>NIM: {!! $data_kelas->nim !!}</p>
                        </code>
                        <hr />
                        <hr />
                        <code>
                            <p>Kelas: {!! $data_kelas->kelas !!}</p>
                        </code>
                        <hr />
                        <hr>
                        <code>
                            <p>Jurusan: {!! $data_kelas->jurusan !!}</p>
                        </code>
                        <hr />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>