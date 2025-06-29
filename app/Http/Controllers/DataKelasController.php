<?php

namespace App\Http\Controllers;

use App\Models\DataKelas;
use App\Imports\DataKelasImport;
use App\Exports\DataKelasExport;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use TCPDF;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Log;

class DataKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $datakelas4b = DataKelas::latest()->paginate(30);
        return view('datakelas4b.index', compact('datakelas4b'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('datakelas4b.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nim' => 'required|string|max:255|unique:data_kelas,nim',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'angkatan' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255'
        ]);

        DataKelas::create($request->all());

        return redirect()->route('datakelas4b.index')->with('success', 'Data Berhasil Disimpan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id
     * @return View
     */
    public function show(string $id): View
    {
        $data_kelas = DataKelas::findOrFail($id);
        return view('datakelas4b.show', compact('data_kelas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $data_kelas = DataKelas::findOrFail($id);
        return view('datakelas4b.edit', compact('data_kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'nim' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'angkatan' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
        ]);

        $data_kelas = DataKelas::findOrFail($id);
        $data_kelas->update($request->all());

        return redirect()->route('datakelas4b.index')->with('success', 'Data Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $data_kelas = DataKelas::findOrFail($id);
        $data_kelas->delete();

        return redirect()->route('datakelas4b.index')->with('success', 'Data Berhasil Dihapus!');
    }

    /**
     * Export the data to an Excel file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new DataKelasExport, 'DataKelas.xlsx');
    }

    /**
     * Import the data from an Excel file.
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        Log::info('Memulai proses import file Excel.', ['filename' => $request->file('file')->getClientOriginalName()]);

        try {
            Excel::import(new DataKelasImport, $request->file('file'));
            Log::info('Proses import file Excel berhasil tanpa error.');
            return redirect()->route('datakelas4b.index')->with('success', 'Data mahasiswa berhasil diimpor!');

        } catch (ValidationException $e) {
            // Log detail error untuk developer
            Log::error('Terjadi ValidationException saat import.', [
                'failures' => $e->failures()
            ]);

            // FIX: Berikan pesan error yang singkat kepada pengguna
            return back()->with('error', 'Pastikan format dan isi file sudah benar.');

        } catch (\Exception $e) {
            // Log detail error untuk developer
            Log::critical('Terjadi Exception umum saat import.', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // FIX: Berikan pesan error yang singkat kepada pengguna
            return back()->with('error', 'Gagal mengimpor data karena terjadi kesalahan pada sistem.');
        }
    }

    /**
     * Generate a PDF of the data.
     *
     * @return StreamedResponse
     */
    public function printpdf(): StreamedResponse
    {
        $data_kelas = DataKelas::orderBy('nama', 'ASC')->get();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Muhammad Rizki Awaluddin Mubin');
        $pdf->SetTitle('Laporan Data Kelas TI 4B');
        $pdf->SetSubject('Data Mahasiswa');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Laporan Data Mahasiswa TI 4B', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 8, 'Jumlah Mahasiswa: ' . $data_kelas->count(), 0, 1, 'L');
        $pdf->Ln(5);
        $drawHeader = function ($pdf) {
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(230, 230, 230);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(128, 128, 128);
            $w = [10, 25, 65, 15, 20, 55];
            $pdf->Cell($w[0], 8, 'No', 1, 0, 'C', 1);
            $pdf->Cell($w[1], 8, 'NIM', 1, 0, 'C', 1);
            $pdf->Cell($w[2], 8, 'Nama', 1, 0, 'C', 1);
            $pdf->Cell($w[3], 8, 'Kelas', 1, 0, 'C', 1);
            $pdf->Cell($w[4], 8, 'Angkatan', 1, 0, 'C', 1);
            $pdf->Cell($w[5], 8, 'Jurusan', 1, 1, 'C', 1);
        };
        $drawHeader($pdf);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetFillColor(255);
        $no = 1;
        foreach ($data_kelas as $isi) {
            $w = [10, 25, 65, 15, 20, 55];
            $rowHeight = $pdf->getStringHeight($w[2], $isi->nama);
            if ($pdf->GetY() + $rowHeight > ($pdf->getPageHeight() - $pdf->getBreakMargin())) {
                $pdf->AddPage();
                $drawHeader($pdf);
                $pdf->SetFont('helvetica', '', 9);
            }
            $pdf->setCellPaddings(2, 2, 2, 2);
            $pdf->MultiCell($w[0], $rowHeight, $no, 1, 'C', 1, 0, '', '', true, 0, false, true, $rowHeight, 'M');
            $pdf->MultiCell($w[1], $rowHeight, $isi->nim, 1, 'C', 1, 0, '', '', true, 0, false, true, $rowHeight, 'M');
            $pdf->MultiCell($w[2], $rowHeight, $isi->nama, 1, 'L', 1, 0, '', '', true, 0, false, true, $rowHeight, 'M');
            $pdf->MultiCell($w[3], $rowHeight, $isi->kelas, 1, 'C', 1, 0, '', '', true, 0, false, true, $rowHeight, 'M');
            $pdf->MultiCell($w[4], $rowHeight, $isi->angkatan, 1, 'C', 1, 0, '', '', true, 0, false, true, $rowHeight, 'M');
            $pdf->MultiCell($w[5], $rowHeight, $isi->jurusan, 1, 'L', 1, 1, '', '', true, 0, false, true, $rowHeight, 'M');
            $no++;
        }
        return response()->streamDownload(function () use ($pdf) {
            $pdf->Output('Laporan_Data_Kelas.pdf', 'I');
        }, 'Laporan_Data_Kelas.pdf');
    }
}
