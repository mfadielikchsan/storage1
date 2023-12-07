<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockFg extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['part', 'gate', 'statusout'];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function gate()
    {
        return $this->belongsTo(Gate::class);
    }

    public function statusout()
    {
        return $this->belongsTo(StatusOut::class);
    }
}
