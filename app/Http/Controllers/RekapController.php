<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RekapController extends Controller
{
    //
    public function index()
    {
        return view('pages.rekap.index');
    }
    public function cetak()
    {
        return view('pages.report.cetak');
    }
}
