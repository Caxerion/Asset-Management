<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBalance extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'floor_id', 'qty_on_hand'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}