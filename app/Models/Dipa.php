<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dipa extends Model
{
    use HasFactory;

    protected $fillable = [
        'satker_id',
        'tahun_anggaran',
        'nomor_dipa',
        'tanggal_dipa',
        'nilai_pagu',
    ];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function getRealisasiAttribute()
    {
        // Hitung realisasi SP2D terverifikasi untuk Satker & Tahun Anggaran ini
        return \App\Models\Sp2d::where('status', 'Terverifikasi')
            ->whereYear('tanggal_sp2d', $this->tahun_anggaran)
            ->whereHas('spm', function ($q) {
                $q->where('satker_id', $this->satker_id);
            })->sum('nilai_sp2d');
    }

    public function getSisaPaguAttribute()
    {
        return $this->nilai_pagu - $this->realisasi;
    }
}
