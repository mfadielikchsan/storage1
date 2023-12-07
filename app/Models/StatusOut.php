<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusOut extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function stockfg()
    {
        return $this->hasMany(StockFg::class);
    }
}
