<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'floor_id', 'trans_type',
        'quantity', 'trans_at', 'pickup_id', 'pickup_line_id',
        'notes', 'created_by', 'updated_by'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function pickup()
    {
        return $this->belongsTo(Pickup::class);
    }

    public function pickupLine()
    {
        return $this->belongsTo(PickupLine::class);
    }
}