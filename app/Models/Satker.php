<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    protected $guarded = ['id'];

    public function ppks()
    {
        return $this->hasMany(Ppk::class);
    }
}
