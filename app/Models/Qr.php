<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{

    protected $table = 'qr';
    protected $guarded = [];


    final public function assets()
    {
        return $this->belongsToMany(Asset::class, 'qr_assets', 'qrId', 'assetId' )
            ->as('assets')
            ->withTimestamps();
    }
}
