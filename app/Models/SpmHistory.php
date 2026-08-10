<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpmHistory extends Model
{
    protected $guarded = ['id'];

    public function spm()
    {
        return $this->belongsTo(Spm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
