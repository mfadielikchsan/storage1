<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockFg extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['part', 'gate', 'StatusOut'];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function gate()
    {
        return $this->belongsTo(Gate::class);
    }

    public function StatusOut()
    {
        return $this->belongsTo(StatusOut::class);
    }
}
