<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'floor_id', 'qty', 'received_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}