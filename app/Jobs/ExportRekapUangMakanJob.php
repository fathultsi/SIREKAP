<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class ExportRekapUangMakanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $data, $bulanTahun, $userId;

    public function __construct($data, $bulanTahun, $userId)
    {
        //
        $this->data = $data;
        $this->bulanTahun = $bulanTahun;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        $cacheKey = 'export_status_' . $this->userId;

        try {
            // ⏳ Status awal: masih diproses
            Cache::put($cacheKey, [
                'status' => 'processing',
            ], now()->addMinutes(30));

            // Nama file ZIP unik
            $zipFileName = 'rekap_uang_makan_' . $this->bulanTahun . '_' . now()->timestamp . '.zip';
            $tempZipPath = storage_path("app/temp/{$zipFileName}");

            // Buat direktori sementara jika belum ada
            if (!file_exists(dirname($tempZipPath))) {
                mkdir(dirname($tempZipPath), 0777, true);
            }

            $zip = new ZipArchive;
            if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                foreach ($this->data as $pegawai) {
                    $pdf = Pdf::loadView('pdf.rekap-uang-makan', [
                        'pegawai' => $pegawai,
                        'bulanTahun' => $this->bulanTahun
                    ]);

                    $pdfFileName = Str::slug($pegawai['nama']) . '.pdf';
                    $zip->addFromString($pdfFileName, $pdf->output());
                }
                $zip->close();
            } else {
                throw new \Exception('Gagal membuat ZIP file.');
            }

            // Simpan ke storage public (storage/app/public/exports)
            $storagePath = 'exports/' . $zipFileName;
            Storage::disk('public')->put($storagePath, file_get_contents($tempZipPath));

            // Bersihkan file temp
            unlink($tempZipPath);

            // ✅ Status selesai
            Cache::put($cacheKey, [
                'status' => 'done',
                'filename' => $zipFileName,
                'file_url' => Storage::url($storagePath)
            ], now()->addMinutes(30));
        } catch (\Exception $e) {
            // ❌ Status gagal
            Cache::put($cacheKey, [
                'status' => 'failed',
                'message' => $e->getMessage(),
            ], now()->addMinutes(30));
            throw $e;
        }

        // $zipFileName = 'rekap_uang_makan_' . $this->bulanTahun . '_' . now()->timestamp . '.zip';
        // $zipPath = storage_path("app/public/exports/{$zipFileName}");

        // if (!file_exists(dirname($zipPath))) {
        //     mkdir(dirname($zipPath), 0777, true);
        // }

        // $zip = new ZipArchive;
        // $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // foreach ($this->data as $pegawai) {
        //     $pdf = Pdf::loadView('pdf.rekap-uang-makan', [
        //         'pegawai' => $pegawai,
        //         'bulanTahun' => $this->bulanTahun
        //     ]);
        //     $pdfFileName = preg_replace('/\s+/', '_', $pegawai['nama']) . '.pdf';
        //     $zip->addFromString($pdfFileName, $pdf->output());
        // }

        // $zip->close();
    }
}
