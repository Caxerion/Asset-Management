<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function stockBalances()
    {
        return $this->hasMany(StockBalance::class);
    }

    public function pickups()
    {
        return $this->hasMany(Pickup::class);
    }
}