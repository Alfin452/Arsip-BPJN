<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sp2d extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nomor_sp2d', 'tanggal_sp2d', 'nilai_sp2d', 'spm_id',
        'keterangan', 'file_pdf', 'status', 'uploaded_by', 'verified_by', 'verified_at'
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
