<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->string('sku', 50)->unique();
            $table->string('name', 150);

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('size_id')->constrained()->cascadeOnDelete();

            $table->string('unit', 20);
            $table->decimal('min_stock', 18, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreignId('created_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
// products
