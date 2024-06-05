<?php

namespace App\Exports;

use App\Models\DataKelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataKelasExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DataKelas::select("nim", "nama", "kelas", "angkatan", "jurusan")->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["NIM", "Nama", "Kelas", "Angkatan", "Jurusan"];
    }
}