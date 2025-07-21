<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Style\Fill;



class ExportRekapUangMakanController extends Controller
{

    public function exportRekapUangMakan(Request $request)
    {
        $data = $request->input('data');
        $bulanTahun = $request->input('bulanTahun');

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Hapus sheet default

        foreach ($data as $pegawai) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle(substr($pegawai['nama'], 0, 30));

            // NIP dan Nama
            $sheet->setCellValue("A1", "NIP");
            $sheet->setCellValue("B1", $pegawai['nip']);
            $sheet->setCellValue("A2", "Nama");
            $sheet->setCellValue("B2", $pegawai['nama']);

            // Header
            $headers = [
                'Tanggal',
                'Hari',
                'Jam Masuk',
                'Absen Masuk',
                'Jam Pulang',
                'Absen Pulang',
                'Uang per Hari',
                'Keterangan'
            ];
            $sheet->fromArray($headers, null, 'A4');

            $row = 5;
            foreach ($pegawai['detail'] as $detail) {
                $sheet->setCellValue("A{$row}", $detail['tanggal']);
                $sheet->setCellValue("B{$row}", $detail['hari']);
                $sheet->setCellValue("C{$row}", $detail['jam_masuk']);
                $sheet->setCellValue("D{$row}", $detail['absen_masuk']);
                $sheet->setCellValue("E{$row}", $detail['jam_pulang']);
                $sheet->setCellValue("F{$row}", $detail['absen_pulang']);
                $sheet->setCellValue("G{$row}", $detail['uang_per_hari']);
                $sheet->setCellValue("H{$row}", $detail['keterangan']);

                // 💡 Warnai jika ada keterangan (kolom H)
                if (!empty(trim($detail['keterangan'])) && $detail['keterangan'] !== "-") {
                    $sheet->getStyle("A{$row}:H{$row}")->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('FAEDD7');
                }

                $row++;
            }

            // Tambah info total
            $row++;
            $sheet->setCellValue("F{$row}", "Jumlah Kotor");
            $sheet->setCellValue("G{$row}", $pegawai['jumlah_kotor']);
            $row++;

            $sheet->setCellValue("F{$row}", "Potongan Pajak");
            $sheet->setCellValue("G{$row}", $pegawai['potongan_pajak']);
            $row++;

            $sheet->setCellValue("F{$row}", "Total Bersih");
            $sheet->setCellValue("G{$row}", $pegawai['total_bersih']);
        }


        // ✅ Tulis dan kirim ke browser
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Rekap_Uang_Makan_' . $bulanTahun . '.xlsx';

        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return Response::make($excelOutput, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Cache-Control' => 'max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}
