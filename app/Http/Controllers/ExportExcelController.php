<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


class ExportExcelController extends Controller
{

    public function exportRekap(Request $request)
    {
        $request->validate([
            'data' => 'required|array',
            'dataExportAbsensiSekali' => 'nullable|array',
            'dataExportTanpaKeterangan' => 'nullable|array',
            'bulanTahun' => 'required',
            'data.*.NAMA' => 'required',
            'data.*.NIP' => 'required'
        ]);

        $data = $request->input('data');
        $absenSekali = $request->input('dataExportAbsensiSekali', []);
        $tanpaKet = $request->input('dataExportTanpaKeterangan', []);
        $bulanTahun = $request->input('bulanTahun');

        $spreadsheet = new Spreadsheet();

        // ======================== SHEET 1: REKAP ========================
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Rekap Absensi');

        // Konfigurasi A4 dan font
        $sheet1->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet1->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet1->getPageSetup()->setFitToPage(true)->setFitToWidth(1)->setFitToHeight(0);
        $sheet1->getPageMargins()->setTop(0.5)->setBottom(0.5)->setLeft(0.5)->setRight(0.5);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);

        // Logo
        $logo = new Drawing();
        $logo->setPath(public_path('templates/dist/img/profile.png'));
        $logo->setHeight(80);
        $logo->setCoordinates('A1');
        $logo->setWorksheet($sheet1);

        // Kop
        $kop = [
            ['KEMENTERIAN AGAMA REPUBLIK INDONESIA', 14, true],
            ['KANTOR KEMENTERIAN AGAMA KABUPATEN BUTON UTARA', 12, true],
            ['Jalan Sara’ea No...Telp..Kode Pos 93672 BURANGA', 9, false],
            ['Website : http://butonutara.kemenag.go.id', 9, false],
            ['e-mail : kabbutonutara@kemenag.go.id', 9, false],
        ];
        foreach ($kop as $i => $item) {
            $row = $i + 1;
            $sheet1->mergeCells("B{$row}:I{$row}");
            $sheet1->setCellValue("B{$row}", $item[0]);
            $sheet1->getStyle("B{$row}")->getFont()->setSize($item[1])->setBold($item[2]);
            $sheet1->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Garis pemisah
        $sheet1->mergeCells("A6:I6");
        $sheet1->getStyle("A6:I6")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

        // Judul
        $sheet1->mergeCells('A8:I8');
        $sheet1->setCellValue('A8', 'REKAPITULASI ABSEN ASN  ' . $bulanTahun);
        $sheet1->getStyle('A8')->getFont()->setBold(true)->setSize(12);
        $sheet1->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header
        // $headers = [
        //     'No.',
        //     'Nama',
        //     'NIP',
        //     'Jml. Hari Masuk',
        //     'Telat Masuk / Cepat Pulang',
        //     'Sekali Absen',
        //     'Cuti',
        //     'Dinas Luar / Hari',
        //     'Tanpa Ket.'
        // ];
        // $sheet1->fromArray($headers, null, 'A10');
        // $sheet1->getStyle('A10:I10')->applyFromArray([
        //     'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'D9D9D9']],
        //     'font' => ['bold' => true],
        //     'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM], 'allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        //     'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        // ]);
        $headers = [
            'No.',
            'Nama',
            'NIP',
            'Jml. Hari Masuk',
            'Telat Masuk / Cepat Pulang',
            'Sekali Absen',
            'Cuti',
            'Dinas Luar / Hari',
            'Tanpa Ket.'
        ];

        // Isi masing-masing sel di baris 10 dan merge vertikal ke baris 11
        $col = 'A';
        foreach ($headers as $header) {
            $sheet1->setCellValue("{$col}10", $header);
            $sheet1->mergeCells("{$col}10:{$col}11");
            $col++;
        }

        // Style untuk header gabungan baris 10-11
        $sheet1->getStyle('A10:I11')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'D9D9D9']
            ],
            'font' => ['bold' => true],
            'borders' => [
                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM],
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ]);

        // Set tinggi baris 11 agar lebih lega
        $sheet1->getRowDimension(11)->setRowHeight(30); // Atau 35 jika perlu lebih tinggi

        // Isi data
        $row = 12;
        foreach ($data as $index => $item) {
            $sheet1->setCellValue("A{$row}", $index + 1);
            $sheet1->setCellValue("B{$row}", $item['NAMA']);
            $sheet1->setCellValueExplicit("C{$row}", $item['NIP'], DataType::TYPE_STRING);
            $sheet1->setCellValue("D{$row}", ($item['H_MASUK'] ?? "") == 0 ? "" : $item['H_MASUK']);
            $sheet1->setCellValue("E{$row}", ($item['TELAT_CEPAT_PULANG'] ?? "") == 0 ? "" : $item['TELAT_CEPAT_PULANG']);
            $sheet1->setCellValue("F{$row}", ($item['ABSEN_SEKALI'] ?? "") == 0 ? "" : $item['ABSEN_SEKALI']);
            $sheet1->setCellValue("G{$row}", ($item['CUTI'] ?? "") == 0 ? "" : $item['CUTI']);
            $sheet1->setCellValue("H{$row}", ($item['DINAS'] ?? "") == 0 ? "" : $item['DINAS']);
            $sheet1->setCellValue("I{$row}", ($item['TANPA_KETERANGAN'] ?? "") == 0 ? "" : $item['TANPA_KETERANGAN']);

            // $sheet1->getStyle("A{$row}:I{$row}")->applyFromArray([
            //     'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            //     'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            // ]);    // Tambahkan style untuk border dan alignment
            $sheet1->getStyle("A{$row}:I{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Tambahkan alignment horizontal center khusus untuk kolom D sampai I
            $sheet1->getStyle("D{$row}:I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Lebar kolom tetap
        $widths = [5, 25, 20, 15, 20, 15, 10, 18, 12];
        foreach (range('A', 'I') as $i => $col) {
            $sheet1->getColumnDimension($col)->setWidth($widths[$i]);
        }

        // Tanda tangan
        $row += 3;
        $sheet1->mergeCells("G{$row}:I{$row}");
        $sheet1->setCellValue("G{$row}", 'Buranga, ' . date('d F Y'));
        $sheet1->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet1->mergeCells("G" . ($row + 3) . ":I" . ($row + 3));
        $sheet1->setCellValue("G" . ($row + 3), '(Drs. LA DIRI, MA)');
        $sheet1->getStyle("G" . ($row + 3))->getFont()->setBold(true);
        $sheet1->getStyle("G" . ($row + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet1->getPageSetup()->setPrintArea("A1:I" . ($row + 5));

        // ======================== SHEET 2: ABSEN SEKALI ========================
        if (!empty($absenSekali)) {
            $sheet2 = $spreadsheet->createSheet();
            $sheet2->setTitle('Absen Sekali');
            $sheet2->getDefaultRowDimension()->setRowHeight(-1);

            $sheet2->fromArray([
                'No',
                'Nama',
                'NIP',
                'Jabatan',
                'Tanggal',
                'Hari',
                'Jam Masuk',
                'Absen Masuk',
                'Telat/Cepat',
                'Jam Pulang',
                'Absen Pulang',
                'PSW',
                'Libur',
                'Jenis Tugas',
                'Keterangan',
                'Keterangan 2',
                'Status Pegawai'
            ], null, 'A1');

            $r = 2;
            foreach ($absenSekali as $i => $d) {
                $sheet2->fromArray([
                    $i + 1,
                    $d['NAMA'] ?? '',
                    $d['NIP'] ?? '',
                    $d['JABATAN'] ?? '',
                    $d['TANGGAL'] ?? '',
                    $d['HARI'] ?? '',
                    $d['JAM MASUK'] ?? '',
                    $d['ABSEN MASUK'] ?? '',
                    $d['CEPAT TELAT'] ?? '',
                    $d['JAM PULANG'] ?? '',
                    $d['ABSEN PULANG'] ?? '',
                    $d['PSW'] ?? '',
                    $d['LIBUR'] ?? '',
                    $d['JENIS TUGAS'] ?? '',
                    $d['KETERANGAN'] ?? '',
                    $d['KETERANGAN 2'] ?? '',
                    $d['STATUS PEGAWAI'] ?? '',
                ], null, "A{$r}");
                $r++;
            }
        }

        // ======================== SHEET 3: TANPA KETERANGAN ========================
        if (!empty($tanpaKet)) {
            $sheet3 = $spreadsheet->createSheet();
            $sheet3->setTitle('Tanpa Keterangan');
            $sheet3->getDefaultRowDimension()->setRowHeight(-1);

            $sheet3->fromArray([
                'No',
                'Nama',
                'NIP',
                'Jabatan',
                'Tanggal',
                'Hari',
                'Jam Masuk',
                'Absen Masuk',
                'Telat/Cepat',
                'Jam Pulang',
                'Absen Pulang',
                'PSW',
                'Libur',
                'Jenis Tugas',
                'Keterangan',
                'Keterangan 2',
                'Status Pegawai'
            ], null, 'A1');

            $r = 2;
            foreach ($tanpaKet as $i => $d) {
                $sheet3->fromArray([
                    $i + 1,
                    $d['NAMA'] ?? '',
                    $d['NIP'] ?? '',
                    $d['JABATAN'] ?? '',
                    $d['TANGGAL'] ?? '',
                    $d['HARI'] ?? '',
                    $d['JAM MASUK'] ?? '',
                    $d['ABSEN MASUK'] ?? '',
                    $d['CEPAT TELAT'] ?? '',
                    $d['JAM PULANG'] ?? '',
                    $d['ABSEN PULANG'] ?? '',
                    $d['PSW'] ?? '',
                    $d['LIBUR'] ?? '',
                    $d['JENIS TUGAS'] ?? '',
                    $d['KETERANGAN'] ?? '',
                    $d['KETERANGAN 2'] ?? '',
                    $d['STATUS PEGAWAI'] ?? '',
                ], null, "A{$r}");
                $r++;
            }
        }

        // ===== EXPORT FILE =====
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Rekap_Absensi_ASN_' . $bulanTahun . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    // public function exportRekap(Request $request)
    // {
    //     $request->validate([
    //         'data' => 'required|array',
    //         'dataExportAbsensiSekali' => 'nullable|array', // data baru
    //         'dataExportTanpaKeterangan' => 'nullable|array', // data baru
    //         'bulanTahun' => 'required',
    //         'data.*.NAMA' => 'required',
    //         'data.*.NIP' => 'required'
    //     ]);

    //     $data = $request->input('data');

    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // ===== SETUP CETAK A4 LEBAR 1 HALAMAN =====
    //     $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
    //     $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
    //     $sheet->getPageMargins()->setTop(0.5)->setBottom(0.5)->setLeft(0.5)->setRight(0.5);
    //     $sheet->getPageSetup()->setFitToPage(true);
    //     $sheet->getPageSetup()->setFitToWidth(1);
    //     $sheet->getPageSetup()->setFitToHeight(0); // tinggi bebas, bisa 2+ halaman jika panjang

    //     // Font default semua 12
    //     $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);

    //     // ===== LOGO =====
    //     $logo = new Drawing();
    //     $logo->setPath(public_path('templates/dist/img/profile.png'));
    //     $logo->setHeight(80);
    //     $logo->setCoordinates('A1');
    //     $logo->setWorksheet($sheet);

    //     // ===== KOP SURAT =====
    //     $kop = [
    //         ['KEMENTERIAN AGAMA REPUBLIK INDONESIA', 14, true],
    //         ['KANTOR KEMENTERIAN AGAMA KABUPATEN BUTON UTARA', 12, true],
    //         ['Jalan Sara’ea No...Telp..Kode Pos 93672 BURANGA', 9, false],
    //         ['Website : http://butonutara.kemenag.go.id', 9, false],
    //         ['e-mail : kabbutonutara@kemenag.go.id', 9, false],
    //     ];
    //     foreach ($kop as $i => $item) {
    //         $row = $i + 1;
    //         $sheet->mergeCells("B{$row}:I{$row}");
    //         $sheet->setCellValue("B{$row}", $item[0]);
    //         $sheet->getStyle("B{$row}")->getFont()->setSize($item[1])->setBold($item[2]);
    //         $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //     }

    //     // ===== GARIS PEMISAH =====
    //     $sheet->mergeCells("A6:I6");
    //     $sheet->getStyle("A6:I6")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

    //     // ===== JUDUL =====
    //     $sheet->mergeCells('A8:I8');
    //     // $sheet->setCellValue('A8', 'REKAPITULASI ABSEN ASN BULAN MEI TAHUN 2025');
    //     $BULAN = $request->input('bulanTahun');;
    //     $sheet->setCellValue('A8', 'REKAPITULASI ABSEN ASN  ' . $BULAN);

    //     $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(12);
    //     $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    //     // ===== HEADER =====
    //     $headers = [
    //         'No.',
    //         'Nama',
    //         'NIP',
    //         'Jml. Hari Masuk',
    //         'Telat Masuk / Cepat Pulang',
    //         'Sekali Absen',
    //         'Cuti',
    //         'Dinas Luar / Hari',
    //         'Tanpa Ket.'
    //     ];
    //     $sheet->fromArray($headers, null, 'A10');

    //     $sheet->getStyle('A10:I10')->applyFromArray([
    //         'fill' => [
    //             'fillType' => Fill::FILL_SOLID,
    //             'color' => ['rgb' => 'D9D9D9']
    //         ],
    //         'font' => ['bold' => true],
    //         'borders' => [
    //             'bottom' => ['borderStyle' => Border::BORDER_MEDIUM],
    //             'allBorders' => ['borderStyle' => Border::BORDER_THIN],
    //         ],
    //         'alignment' => [
    //             'horizontal' => Alignment::HORIZONTAL_CENTER,
    //             'vertical' => Alignment::VERTICAL_CENTER
    //         ]
    //     ]);

    //     // ===== ISI DATA =====
    //     $row = 11;
    //     foreach ($data as $index => $item) {
    //         $sheet->setCellValue("A{$row}", $index + 1);
    //         $sheet->setCellValue("B{$row}", $item['NAMA']);
    //         $sheet->setCellValueExplicit("C{$row}", $item['NIP'], DataType::TYPE_STRING);
    //         $sheet->setCellValue("D{$row}", $item['H_MASUK'] ?? 0);
    //         $sheet->setCellValue("E{$row}", $item['TELAT_CEPAT_PULANG'] ?? 0);
    //         $sheet->setCellValue("F{$row}", $item['ABSEN_SEKALI'] ?? 0);
    //         $sheet->setCellValue("G{$row}", $item['CUTI'] ?? 0);
    //         $sheet->setCellValue("H{$row}", $item['DINAS'] ?? 0);
    //         $sheet->setCellValue("I{$row}", $item['TANPA_KETERANGAN'] ?? 0);

    //         $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
    //             'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    //             'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
    //         ]);
    //         $row++;
    //     }

    //     // ===== LEBAR KOLOM TETAP (tidak autoSize) =====
    //     $widths = [5, 25, 20, 15, 20, 15, 10, 18, 12]; // disesuaikan
    //     foreach (range('A', 'I') as $i => $col) {
    //         $sheet->getColumnDimension($col)->setWidth($widths[$i]);
    //     }

    //     // ===== TANDA TANGAN (teks saja) =====
    //     $row += 3;
    //     $sheet->mergeCells("G{$row}:I{$row}");
    //     $sheet->setCellValue("G{$row}", 'Buranga, ' . date('d F Y'));
    //     $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    //     $sheet->mergeCells("G" . ($row + 3) . ":I" . ($row + 3));
    //     $sheet->setCellValue("G" . ($row + 3), '(Drs. LA DIRI, MA)');
    //     $sheet->getStyle("G" . ($row + 3))->getFont()->setBold(true);
    //     $sheet->getStyle("G" . ($row + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    //     // ===== PRINT AREA FIXED SESUAI PANJANG DATA =====
    //     $lastRow = $row + 5;
    //     $sheet->getPageSetup()->setPrintArea("A1:I{$lastRow}");

    //     // ===== EXPORT FILE =====
    //     $writer = new Xlsx($spreadsheet);
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     // header('Content-Disposition: attachment;filename="Rekap_Absensi_ASN.xlsx"');
    //     header('Content-Disposition: attachment;filename="Rekap_Absensi_ASN_' . $BULAN . '.xlsx"');
    //     header('Cache-Control: max-age=0');
    //     $writer->save('php://output');
    //     exit;
    // }
}
