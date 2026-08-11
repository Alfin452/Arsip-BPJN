<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PaketPekerjaan extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'satker_id',
        'ppk_id',
        'penyedia_id',
        'nama_paket',
        'nomor_kontrak',
        'tanggal_kontrak',
        'nilai_kontrak',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function ppk()
    {
        return $this->belongsTo(Ppk::class);
    }

    public function penyedia()
    {
        return $this->belongsTo(Penyedia::class);
    }

    public function basts()
    {
        return $this->hasMany(Bast::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
