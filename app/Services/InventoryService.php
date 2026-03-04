<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\StockBalance;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function receive($receipt, $lines)
    {
        foreach ($lines as $line) {
            InventoryTransaction::create([
                'trans_at' => now(),
                'trans_type' => 'IN',
                'product_id' => $line['product_id'],
                'floor_id' => 1, // default floor
                'quantity' => $line['qty'],
            ]);

            StockBalance::updateOrCreate(
                [
                    'product_id' => $line['product_id'],
                    'floor_id' => 1,
                ],
                [
                    'qty_on_hand' => DB::raw('qty_on_hand + '.$line['qty'])
                ]
            );
        }
    }
}
