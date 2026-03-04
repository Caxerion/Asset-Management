<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pickup;
use App\Models\PickupLine;
use App\Models\Product;
use App\Models\Floor;
use App\Models\User;

echo "=== Testing Model Relationships ===\n\n";

// 1. Test Pickup with user, floor, items (pickup lines) + product
echo "1. Pickup with user, floor, items.product:\n";
$pickup = Pickup::with(['user', 'floor', 'items.product'])->first();
if ($pickup) {
    echo "   - Pickup ID: " . $pickup->id . "\n";
    echo "   - Pickup No: " . $pickup->pickup_no . "\n";
    echo "   - User (requested_by): " . ($pickup->user ? $pickup->user->name : 'N/A') . "\n";
    echo "   - Floor: " . ($pickup->floor ? $pickup->floor->name : 'N/A') . "\n";
    echo "   - Items count: " . $pickup->items->count() . "\n";
    foreach ($pickup->items as $item) {
        echo "      - Product: " . ($item->product ? $item->product->name : 'N/A') . ", Qty: " . $item->qty . "\n";
    }
    echo "   ✓ Success!\n";
} else {
    echo "   - No Pickup data found\n";
}
echo "\n";

// 2. Test PickupLine with pickup and product
echo "2. PickupLine with pickup, product:\n";
$pickupLine = PickupLine::with(['pickup', 'product'])->first();
if ($pickupLine) {
    echo "   - PickupLine ID: " . $pickupLine->id . "\n";
    echo "   - Pickup: " . ($pickupLine->pickup ? $pickupLine->pickup->pickup_no : 'N/A') . "\n";
    echo "   - Product: " . ($pickupLine->product ? $pickupLine->product->name : 'N/A') . "\n";
    echo "   - Quantity: " . $pickupLine->qty . "\n";
    echo "   ✓ Success!\n";
} else {
    echo "   - No PickupLine data found\n";
}
echo "\n";

// 3. Test Product with category and size
echo "3. Product with category, size:\n";
$product = Product::with(['category', 'size'])->first();
if ($product) {
    echo "   - Product ID: " . $product->id . "\n";
    echo "   - Product Name: " . $product->name . "\n";
    echo "   - SKU: " . $product->sku . "\n";
    echo "   - Category: " . ($product->category ? $product->category->name : 'N/A') . "\n";
    echo "   - Size: " . ($product->size ? $product->size->name : 'N/A') . "\n";
    echo "   ✓ Success!\n";
} else {
    echo "   - No Product data found\n";
}
echo "\n";

// 4. Test Floor with stockBalances
echo "4. Floor with stockBalances:\n";
$floor = Floor::with(['stockBalances'])->first();
if ($floor) {
    echo "   - Floor ID: " . $floor->id . "\n";
    echo "   - Floor Name: " . $floor->name . "\n";
    echo "   - StockBalances count: " . $floor->stockBalances->count() . "\n";
    echo "   ✓ Success!\n";
} else {
    echo "   - No Floor data found\n";
}
echo "\n";

// 5. Test User with roles
echo "5. User with roles:\n";
$user = User::with(['roles'])->first();
if ($user) {
    echo "   - User ID: " . $user->id . "\n";
    echo "   - User Name: " . $user->name . "\n";
    echo "   - Email: " . $user->email . "\n";
    echo "   - Roles count: " . $user->roles->count() . "\n";
    foreach ($user->roles as $role) {
        echo "      - Role: " . $role->name . "\n";
    }
    echo "   ✓ Success!\n";
} else {
    echo "   - No User data found\n";
}
echo "\n";

echo "=== All Tests Completed ===\n";
