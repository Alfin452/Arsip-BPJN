<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpmAttachment extends Model
{
    protected $guarded = ['id'];

    public function spm()
    {
        return $this->belongsTo(Spm::class);
    }
}
