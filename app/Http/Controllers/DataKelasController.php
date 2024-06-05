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
            'nim' => 'required|string|max:255',
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

        Excel::import(new DataKelasImport, $request->file('file')->store('temp'));

        return back()->with('success', 'DataKelas imported successfully.');
    }
    /**
     * Generate a PDF of the data.
     *
     * @return \Illuminate\Http\Response
     */
    public function printpdf()
    {
        $data_kelas = DataKelas::all();

        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Muhammad Rizki Awaluddin Mubin');
        $pdf->SetTitle('Data Kelas TI 4B');
        $pdf->SetSubject('Data Kelas TI 4B');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 25);
        $pdf->Cell(0, 10, 'Data Kelas TI 4B', 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 8, 'Jumlah Mahasiswa: ' . count($data_kelas), 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(8, 10, 'No', 1, 0, 'C');
        $pdf->Cell(20, 10, 'NIM', 1, 0, 'C');
        $pdf->Cell(84, 10, 'Nama', 1, 0, 'C');
        $pdf->Cell(15, 10, 'Kelas', 1, 0, 'C');
        $pdf->Cell(18, 10, 'Angkatan', 1, 0, 'C');
        $pdf->Cell(45, 10, 'Jurusan', 1, 1, 'C');

        $pdf->SetFont('helvetica', '', 8);
        $no = 1;
        foreach ($data_kelas as $isi) {
            $pdf->setCellPadding(3);
            $pdf->Cell(8, 10, $no, 1, 0, 'C');
            $pdf->Cell(20, 10, $isi->nim, 1, 0, 'C');
            $pdf->MultiCell(84, 10, $isi->nama, 1, 'L', 0, 0, '', '', true);
            $pdf->Cell(15, 10, $isi->kelas, 1, 0, 'C');
            $pdf->Cell(18, 10, $isi->angkatan, 1, 0, 'C');
            $pdf->Cell(45, 10, $isi->jurusan, 1, 1, 'C');
            $no++;
        }

        return response()->streamDownload(function() use ($pdf) {
            $pdf->Output('datakelas.pdf', 'I');
        }, 'datakelas.pdf');
    }
}