<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// // use Barryvdh\DomPDF\Facade\Pdf;
// use Illuminate\Support\Facades\Storage;
// use ZipArchive;
// use Illuminate\Support\Facades\Log;
// use App\Jobs\ExportRekapUangMakanJob;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Str;
// use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
// // url
// // use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use Illuminate\Http\Request;
use App\Jobs\ExportRekapUangMakanJob;
use Spatie\Browsershot\Browsershot;






class ExportRekapUangMakanZipController extends Controller
{

    public function tes()
    {
        return view('pdf.tes');
    }

    public function requestExport(Request $request)
    {
        $data = $request->input('data');
        $bulanTahun = $request->input('bulanTahun');
        $userId = 'admin'; // pastikan user login

        // Tandai sebagai sedang diproses (opsional, karena job juga menandai)
        Cache::put('export_status_' . $userId, [
            'status' => 'processing',
        ], now()->addMinutes(30));

        ExportRekapUangMakanJob::dispatch($data, $bulanTahun, $userId);

        return response()->json(['message' => 'Export sedang diproses']);
    }

    public function checkStatus()
    {
        $userId = 'admin';
        $status = Cache::get('export_status_' . $userId);

        if (!$status) {
            return response()->json(['status' => 'not_found']);
        }

        return response()->json([
            'status' => $status['status'],
            'file_url' => isset($status['filename'])
                ? asset('storage/exports/' . $status['filename'])
                : null,
        ]);
    }

    // public function requestExport(Request $request)
    // {
    //     $data = $request->input('data');
    //     $bulanTahun = $request->input('bulanTahun');

    //     ExportRekapUangMakanJob::dispatch($data, $bulanTahun, 'admin');

    //     return response()->json([
    //         'message' => 'Export sedang diproses di background. Nanti akan tersedia untuk diunduh.'
    //     ]);
    // }

    public function getProgress()
    {
        $key = 'export_rekap_progress_' . 'admin';
        return response()->json(Cache::get($key, [
            'current' => 0,
            'total' => 1,
            'nama' => 'Menunggu...'
        ]));
    }

    public function exportRekapUangMakanPreview(Request $request)
    {
        $data = json_decode($request->input('data'), true); // Karena dikirim dalam bentuk JSON string
        $bulanTahun = $request->input('bulanTahun');

        if (empty($data)) {
            return response('Data kosong', 400);
        }

        $pegawai = $data[0]; // Ambil hanya pegawai pertama
        $nama = $pegawai['nama'];

        $html = view('pdf.rekap-uang-makan', compact('pegawai'))->render();

        $fileName = 'rekap_' . $pegawai['nip'] . '.pdf';
        $filePath = storage_path('app/public/' . $fileName);

        Browsershot::html($html)
            ->format('A4')
            ->margins(10, 10, 10, 10)
            // ->setOption('args', ['--no-sandbox']) // Wajib kalau di Linux server
            ->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend();



        $pdf = Pdf::loadView('pdf.rekap-uang-makan', [
            'pegawai' => $pegawai,
            'bulanTahun' => $bulanTahun,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream(Str::slug($nama) . '.pdf');
    }



    public function exportRekapUangMakanZip(Request $request)
    {





        $userId = 'admin';
        $cacheKey = "export_rekap_progress_$userId";

        $data = $request->input('data');
        $bulanTahun = $request->input('bulanTahun');
        $zipFileName = 'Laporan_pdf_Uang_Makan_' . Str::slug($bulanTahun) . '.zip';
        $zipRelativePath = 'public/' . $zipFileName;

        // Hapus file lama jika ada
        Storage::delete($zipRelativePath);

        // Buat direktori kalau belum ada
        Storage::makeDirectory('public');

        $fullZipPath = Storage::path($zipRelativePath);

        $zip = new ZipArchive;
        if ($zip->open($fullZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return response()->json(['error' => 'Tidak bisa membuka ZIP file.'], 500);
        }

        Log::info('🔍 Mulai proses export ZIP');
        $total = count($data);

        foreach ($data as $index => $pegawai) {
            $current = $index + 1;
            $nama = $pegawai['nama'];

            Cache::put($cacheKey, [
                'current' => $current,
                'total' => $total,
                'nama' => $nama,
            ], now()->addMinutes(5));

            Log::info('📄 Membuat PDF', ['nama' => $nama]);

            // $pdf = Pdf::loadView('pdf.rekap-uang-makan', [
            //     'pegawai' => $pegawai,
            //     'bulanTahun' => $bulanTahun,
            // ])->setPaper('a4', 'portrait');
            $pdf = Pdf::loadView('pdf.rekap-uang-makan', [
                'pegawai' => $pegawai,
                'bulanTahun' => 'Juli 2025'
            ]);

            return $pdf->stream('rekap-uang-makan.pdf');
            break;

            $pdfFileName = Str::slug($nama) . '.pdf';
            $zip->addFromString($pdfFileName, $pdf->output());
        }

        $zip->close();

        if (!file_exists($fullZipPath)) {
            Log::error("❌ ZIP file tidak ditemukan: $fullZipPath");
            return response()->json(['error' => 'ZIP file gagal dibuat.'], 500);
        }

        chmod($fullZipPath, 0644);
        Log::info("✅ ZIP berhasil dibuat: $fullZipPath");

        $downloadUrl = Storage::url($zipRelativePath);

        Cache::put($cacheKey, [
            'current' => $total,
            'total' => $total,
            'nama' => 'Selesai',
            'download_url' => $downloadUrl,
        ], now()->addMinutes(10));

        Log::info('✅ Proses selesai. Cache akhir disimpan.', ['download_url' => $downloadUrl]);

        return response()->json(['download_url' => $downloadUrl]);
    }


    // public function exportRekapUangMakanZip(Request $request)
    // {
    //     $userId = 'admin';
    //     $cacheKey = 'export_rekap_progress_' . $userId;
    //     // $downloadUrl = 'storage/exports/'; // URL untuk downloadUrl

    //     $data = $request->input('data');
    //     $bulanTahun = $request->input('bulanTahun');
    //     // dispatch(function () use ($data, $bulanTahun, $cacheKey, $userId) {
    //     $zipFileName = 'Laporan_pdf_Uang_Makan_' . 'JULI_2025'  . '.zip';
    //     $zipFilePath = 'public/' . $zipFileName;

    //     // Hapus file lama jika ada
    //     if (Storage::exists($zipFilePath)) {
    //         Storage::delete($zipFilePath);
    //     }

    //     // Ambil full path untuk digunakan ZipArchive
    //     $fullZipPath = Storage::path($zipFilePath);

    //     // Pastikan direktori tujuan ada
    //     Storage::makeDirectory('public');


    //     $zip = new ZipArchive;
    //     if ($zip->open($fullZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    //         return response()->json(['error' => 'Tidak bisa membuka ZIP file.'], 500);
    //     }

    //     $total = count($data);
    //     Log::info('🔍 Mulai proses export ZIP');
    //     foreach ($data as $index => $pegawai) {
    //         Cache::put($cacheKey, [
    //             'current' => $index + 1,
    //             'total' => $total,
    //             'nama' => $pegawai['nama'],
    //         ], now()->addMinutes(5));

    //         $pdf = PDF::loadView('pdf.rekap-uang-makan', [
    //             'pegawai' => $pegawai,
    //             'bulanTahun' => $bulanTahun
    //         ])->setOption('enable-local-file-access', true)
    //             ->setPaper('a4')
    //             ->setOrientation('portrait');


    //         Log::info('🔍 Membuat PDF', ['pegawai' => $pegawai['nama']]);

    //         $pdfFileName = Str::slug($pegawai['nama']) . '.pdf';
    //         $zip->addFromString($pdfFileName, $pdf->output());
    //     }

    //     $zip->close();
    //     if (!file_exists($fullZipPath)) {
    //         Log::error("❌ File ZIP tidak ditemukan: $fullZipPath");
    //     } else {
    //         Log::info("✅ File ZIP berhasil dibuat: $fullZipPath");
    //     }
    //     // Atur permission file ZIP
    //     chmod($fullZipPath, 0644);

    //     Log::info('🔍 Selesai membuat semua PDF');

    //     // Simpan info download ke cache
    //     $downloadUrl = Storage::url($zipFilePath); // otomatis ke "storage/..."

    //     Cache::put($cacheKey, [
    //         'current' => $total,
    //         'total' => $total,
    //         'nama' => 'Selesai',
    //         'download_url' => $downloadUrl,
    //     ], now()->addMinutes(5));

    //     Log::info('✅ Cache akhir (selesai):', [
    //         'key' => $cacheKey,
    //         'value' => [
    //             'current' => $total,
    //             'total' => $total,
    //             'nama' => 'Selesai',
    //             'download_url' => $downloadUrl,
    //         ]
    //     ]);
    //     // });
    //     return response()->json(['download_url' => 'dsads']);
    // }


    // public function exportRekapUangMakanZip(Request $request)
    // {
    //     $userId = 'admin';
    //     $cacheKey = 'export_rekap_progress_' . $userId;

    //     $data = $request->input('data');
    //     $bulanTahun = $request->input('bulanTahun');

    //     // Jalankan dispatch ke background process
    //     // dispatch(function () use ($data, $bulanTahun, $cacheKey, $userId) {
    //     $zipFileName = 'Laporan_pdf_Uang_Makan_' . 'JULI_2025'  . '.zip';
    //     $zipPath = storage_path("app/public/{$zipFileName}");

    //     // Hapus file lama jika ada
    //     if (file_exists($zipPath)) {
    //         unlink($zipPath);
    //     }
    //     // Buat folder jika belum ada
    //     $storageDir = dirname($zipPath);
    //     if (!file_exists($storageDir)) {
    //         mkdir($storageDir, 0777, true);
    //     }

    //     $zip = new ZipArchive;
    //     $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    //     $total = count($data);
    //     Log::info('🔍 Mulai proses export ZIP');

    //     foreach ($data as $index => $pegawai) {
    //         // Update progres cache
    //         Cache::put($cacheKey, [
    //             'current' => $index + 1,
    //             'total' => $total,
    //             'nama' => $pegawai['nama'],
    //         ], now()->addMinutes(5));

    //         $pdf = PDF::loadView('pdf.rekap-uang-makan', [
    //             'pegawai' => $pegawai,
    //             'bulanTahun' => $bulanTahun
    //         ]);
    //         Log::info('🔍 Membuat PDF', ['pegawai' => $pegawai['nama']]);

    //         $pdfFileName = Str::slug($pegawai['nama']) . '.pdf';
    //         $zip->addFromString($pdfFileName, $pdf->output());
    //     }

    //     $zip->close();
    //     if (file_exists($zipPath)) {
    //         chmod($zipPath, 0644);
    //     }
    //     // array_map('unlink', glob(storage_path('app/temp/*.pdf')));

    //     Log::info('🔍 Selesai membuat semua PDF');


    //     // Tambahkan URL unduhan ke cache
    //     Cache::put($cacheKey, [
    //         'current' => $total,
    //         'total' => $total,
    //         'nama' => 'Selesai',
    //         'download_url' => URL::to('storage/' . $zipFileName),
    //     ], now()->addMinutes(5));
    //     Log::info('✅ Cache akhir (selesai):', [
    //         'key' => $cacheKey,
    //         'value' => [
    //             'current' => $total,
    //             'total' => $total,
    //             'nama' => 'Selesai',
    //             'download_url' => URL::to('storage/' . $zipFileName),
    //         ]
    //     ]);


    //     return response()->json(['status' => 'started']);
    // }

    // public function exportRekapUangMakanZip(Request $request)
    // {


    //     $userId = 'admin';
    //     $cacheKey = 'export_rekap_progress_' . $userId;

    //     $data = $request->input('data');
    //     $bulanTahun = $request->input('bulanTahun');
    //     $zipFileName = 'Rekap_Uang_Makan_' . $bulanTahun . '_' . now()->timestamp . '.zip';
    //     // $tempPath = storage_path('app/temp-pdf');

    //     // if (!file_exists($tempPath)) {
    //     //     mkdir($tempPath, 0777, true);
    //     // }

    //     $zipPath = storage_path("app/public/{$zipFileName}");
    //     if (!file_exists(dirname($zipPath))) {
    //         mkdir(dirname($zipPath), 0777, true);
    //     }

    //     $zip = new ZipArchive;
    //     $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);



    //     $total = count($data);
    //     Log::info('🔍 Mulai proses export ZIP');
    //     foreach ($data as $index => $pegawai) {
    //         // Simpan progres di cache
    //         Cache::put($cacheKey, [
    //             'current' => $index + 1,
    //             'total' => $total,
    //             'nama' => $pegawai['nama'],
    //         ], now()->addMinutes(5));

    //         // Buat PDF
    //         $pdf = PDF::loadView('pdf.rekap-uang-makan', [
    //             'pegawai' => $pegawai,
    //             'bulanTahun' => $bulanTahun
    //         ]);
    //         Log::info('🔍 Membuat PDF', ['pegawai' => $pegawai['nama']]);

    //         // $pdfFileName = Str::slug($pegawai['nama']) . '.pdf';
    //         // $pdfFullPath = "{$tempPath}/{$pdfFileName}";
    //         // file_put_contents($pdfFullPath, $pdf->output());
    //         // $zip->addFile($pdfFullPath, $pdfFileName);

    //         $pdfFileName = Str::slug($pegawai['nama']) . '.pdf';
    //         $zip->addFromString($pdfFileName, $pdf->output());
    //     }

    //     Log::info('🔍 Selesai membuat semua PDF');

    //     $zip->close();

    //     // Bersihkan file PDF sementara
    //     // foreach (glob($tempPath . '/*.pdf') as $file) {
    //     //     unlink($file);
    //     // }

    //     // Hapus cache
    //     Cache::forget($cacheKey);

    //     return response()->json([
    //         'status' => 'done',
    //         'download_url' => asset('storage/' . $zipFileName),
    //     ]);
    // }



    // public function exportRekapUangMakanZip(Request $request)
    // {

    //     Log::info('🔍 Mulai proses export ZIP');
    //     $data = $request->input('data');
    //     $bulanTahun = $request->input('bulanTahun');
    //     $zipFileName = 'Rekap_Uang_Makan_' . $bulanTahun . '.zip';
    //     $tempPath = storage_path('app/temp-pdf');

    //     if (!file_exists($tempPath)) {
    //         mkdir($tempPath, 0777, true);
    //     }

    //     $zipPath = storage_path("app/{$zipFileName}");
    //     $zip = new ZipArchive;
    //     $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    //     Log::info('Jumlah pegawai:', ['count' => count($request->input('data'))]);
    //     foreach ($data as $pegawai) {
    //         Log::info('Memproses pegawai:', ['nama' => $pegawai['nama']]);
    //         $pdf = Pdf::loadView('pdf.rekap-uang-makan', [
    //             'pegawai' => $pegawai,
    //             'bulanTahun' => $bulanTahun
    //         ]);

    //         $pdfFileName = preg_replace('/\s+/', '_', $pegawai['nama']) . '.pdf';
    //         $pdfFullPath = "{$tempPath}/{$pdfFileName}";

    //         file_put_contents($pdfFullPath, $pdf->output());
    //         $zip->addFile($pdfFullPath, $pdfFileName);
    //     }
    //     Log::info('✅ Proses selesai, ZIP akan dikirim ke browser');

    //     $zip->close();

    //     // Hapus PDF sementara
    //     foreach (glob($tempPath . '/*.pdf') as $file) {
    //         unlink($file);
    //     }

    //     return response()->download($zipPath)->deleteFileAfterSend(true);
    // }
}
