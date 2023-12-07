<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['customer'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function stockfg()
    {
        return $this->hasMany(StockFg::class);
    }
}
