<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan',
        'npwp',
        'nama_direktur',
        'bank',
        'no_rekening',
    ];

    public function paketPekerjaans()
    {
        return $this->hasMany(PaketPekerjaan::class);
    }
}
