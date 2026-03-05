<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupLine extends Model
{
    use HasFactory;

    protected $fillable = ['pickup_id', 'product_id', 'qty', 'notes', 'created_by', 'updated_by'];

    public function pickup()
    {
        return $this->belongsTo(Pickup::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
