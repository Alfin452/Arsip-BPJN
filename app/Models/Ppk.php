<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppk extends Model
{
    protected $guarded = ['id'];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }
}
