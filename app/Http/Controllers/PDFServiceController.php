<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Carbon\Carbon;



class PDFServiceController extends Controller
{

    // public function generateZip(Request $request)
    // {
    //     $pegawaiList = $request->input('pegawai');  // array of pegawai
    //     $bulanTahun = $request->input('bulanTahun'); // contoh: "Agustus 2025"

    //     if (!$pegawaiList || !$bulanTahun) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Data pegawai atau bulanTahun tidak lengkap.'
    //         ], 422);
    //     }

    //     try {
    //         // Kirim data ke Python
    //         $response = Http::timeout(1000)->post('http://127.0.0.1:8000/generate-pdf', [
    //             'pegawai_list' => $pegawaiList,
    //             'bulanTahun' => $bulanTahun
    //         ]);

    //         if ($response->successful()) {
    //             // Simpan ZIP hasil dari Python
    //             $zipName = 'Rekap_Uang_Makan_' . now()->format('Ymd_His') . '.zip';
    //             Storage::put("public/exports/{$zipName}", $response->body());

    //             return response()->json([
    //                 'success' => true,
    //                 'download_url' => asset("storage/exports/{$zipName}")
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Gagal dari service Python',
    //                 'error' => $response->body()
    //             ], 500);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan saat menghubungi service Python.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function generateZip(Request $request)
    {
        // jalankan sericve python masuk dlu  cd pdf-service/ lalu jalankan
        // uvicorn main:app --reload

        $data = [
            'pegawai_list' => $request->input('pegawai_list'), // yang berisi array pegawai
            'bulan' => $request->input('bulanTahun'),     // string "JULI 2025"
        ];

        foreach ($data['pegawai_list'] as &$pegawai) {
            foreach ($pegawai['detail'] as &$d) {
                $d['tanggal_formatted'] = \Carbon\Carbon::parse($d['tanggal'])->format('d-m-Y');
            }
        }


        $response = Http::timeout(1000)->post('http://127.0.0.1:8000/generate-zip', $data);

        if ($response->successful()) {
            // Simpan ZIP ke storage Laravel
            $zipName = 'Rekap_Uang_Makan_' . $request->input('bulanTahun') . '.zip';
            Storage::disk('public')->put("exports/{$zipName}", $response->body());

            return response()->json([
                'success' => true,
                'download_url' => asset("storage/exports/{$zipName}")
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal dari service Python',
                'error' => $response->body()
            ], 500);
        }
    }
    public function generate_kehadiran(Request $request)
    {
        // jalankan sericve python masuk dlu  cd pdf-service/ lalu jalankan
        // uvicorn main:app --reload

        $data = [
            'pegawai_list' => $request->input('pegawai_list'), // yang berisi array pegawai
            'bulan' => $request->input('bulanTahun'),     // string "JULI 2025"
        ];

        foreach ($data['pegawai_list'] as &$pegawai) {
            foreach ($pegawai['detail'] as &$d) {
                $d['tanggal_formatted'] = Carbon::parse($d['TANGGAL'])->format('d-m-Y');
            }
        }


        $response = Http::timeout(1000)->post('http://127.0.0.1:8000/generate_kehadiran', $data);

        if ($response->successful()) {
            // Simpan ZIP ke storage Laravel
            $zipName = 'Rekap_kehadiran_' . $request->input('bulanTahun') . '.zip';
            Storage::disk('public')->put("exports/{$zipName}", $response->body());

            return response()->json([
                'success' => true,
                'download_url' => asset("storage/exports/{$zipName}")
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal dari service Python',
                'error' => $response->body()
            ], 500);
        }
    }
}
