<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Spm extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function ppk()
    {
        return $this->belongsTo(Ppk::class);
    }

    public function attachments()
    {
        return $this->hasMany(SpmAttachment::class);
    }

    public function histories()
    {
        return $this->hasMany(SpmHistory::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function sp2d()
    {
        return $this->hasOne(Sp2d::class);
    }
}
