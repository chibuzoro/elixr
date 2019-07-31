<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'asset';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asset_type',
        'asset_path',
    ];


    final public function qrs()
    {
        return $this->belongsToMany(Qr::class,'qr_assets','assetId','qrId');
    }
}
