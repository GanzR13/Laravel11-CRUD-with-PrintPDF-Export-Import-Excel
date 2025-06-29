<?php

namespace App\Exports;

use App\Models\DataKelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataKelasExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private $rowNumber = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // FIX: Mengambil data dan langsung mengurutkannya berdasarkan nama (A-Z)
        return DataKelas::orderBy('nama', 'asc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // FIX: Menambahkan kolom "No" pada header
        return [
            "No",
            "NIM",
            "Nama",
            "Kelas",
            "Angkatan",
            "Jurusan"
        ];
    }

    /**
     * Memetakan data yang akan diexport dan menambahkan nomor urut.
     * @param mixed $data_kelas
     * @return array
     */
    public function map($data_kelas): array
    {
        // FIX: Menambahkan nomor urut di setiap baris
        return [
            ++$this->rowNumber,
            $data_kelas->nim,
            $data_kelas->nama,
            $data_kelas->kelas,
            $data_kelas->angkatan,
            $data_kelas->jurusan,
        ];
    }

    /**
     * Mengatur lebar kolom agar tabel terlihat rapi.
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // NIM
            'C' => 40,  // Nama
            'D' => 10,  // Kelas
            'E' => 15,  // Angkatan
            'F' => 30,  // Jurusan
        ];
    }

    /**
     * Menerapkan style pada sheet Excel.
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Mendapatkan baris terakhir yang berisi data
        $lastRow = $sheet->getHighestRow();

        // Menerapkan style ke seluruh tabel (A1 sampai F + baris terakhir)
        $sheet->getStyle('A1:F' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        // Menerapkan style khusus untuk header (baris 1)
        return [
            // Style the first row as bold text.
            1    => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD3D3D3'], // Warna abu-abu muda
                ]
            ],
        ];
    }
}
