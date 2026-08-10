<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp2d extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_sp2d', 'tanggal_sp2d', 'nilai_sp2d', 'spm_id',
        'keterangan', 'file_pdf', 'status', 'uploaded_by', 'verified_by'
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function spm()
    {
        return $this->belongsTo(Spm::class);
    }
}
