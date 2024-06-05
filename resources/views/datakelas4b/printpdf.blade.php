<table border="1" >
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Angkatan</th>
            <th>Jurusan</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($getKelas as $isi): ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $isi['nim']; ?></td>
                <td><?= $isi['nama']; ?></td>
                <td><?= $isi['kelas']; ?></td>
                <td><?= $isi['angkatan']; ?></td>
                <td><?= $isi['jurusan']; ?></td>
            </tr>
        <?php $no++; endforeach; ?>
    </tbody>
</table>