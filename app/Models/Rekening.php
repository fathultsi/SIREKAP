<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    //


    protected $fillable = [
        'nik',
        'nama',
        'no_rekening',
        'nama_bank',
    ];
}
