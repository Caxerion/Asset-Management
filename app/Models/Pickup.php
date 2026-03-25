<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    use HasFactory;

    protected $fillable = ['pickup_no', 'pickup_date', 'requested_by', 'floor_id', 'notes', 'created_by', 'updated_by'];

    public function user()
    {
        return $this->belongsTo(Person::class, 'requested_by');
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function items()
    {
        return $this->hasMany(PickupLine::class);
    }
}
