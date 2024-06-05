<?php

namespace App\Imports;

use App\Models\DataKelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataKelasImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new DataKelas([
            'nim' => $row['nim'],
            'nama' => $row['nama'],
            'kelas' => $row['kelas'],
            'angkatan' => $row['angkatan'],
            'jurusan' => $row['jurusan']
        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function rules(): array
    {
        return [
            'nim' => 'required',
            'nama' => 'required',
            'kelas' => 'required',
            'angkatan' => 'required',
            'jurusan' => 'required'
        ];
    }
}