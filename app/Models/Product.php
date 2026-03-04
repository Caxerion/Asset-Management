<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'name', 'category_id', 'size_id',
        'unit', 'min_stock', 'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function stockBalance()
    {
        return $this->hasOne(StockBalance::class);
    }

    public function stockBalances()
    {
        return $this->hasMany(StockBalance::class);
    }

    // Accessor for stock_balance attribute used in views - shows total stock across all floors
    public function getStockBalanceAttribute()
    {
        // Get the sum of all stock balances for this product across all floors
        return $this->stockBalances()->sum('qty_on_hand');
    }
}
