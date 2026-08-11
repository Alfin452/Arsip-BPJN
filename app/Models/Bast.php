<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bast extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_bast' => 'date',
        'tanggal_penagihan' => 'date',
        'verified_at' => 'datetime',
    ];

    public function paketPekerjaan()
    {
        return $this->belongsTo(PaketPekerjaan::class, 'paket_pekerjaan_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
