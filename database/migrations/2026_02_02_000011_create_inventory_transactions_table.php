<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->dateTime('trans_at');
            $table->enum('trans_type', ['IN', 'OUT']);

            $table->foreignId('product_id')->constrained();
            $table->foreignId('floor_id')->constrained();

            $table->decimal('quantity', 18, 4);
            $table->decimal('unit_price', 18, 4)->nullable();
            $table->string('currency_code', 3)->default('IDR');

            $table->foreignId('pickup_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pickup_line_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('receipt_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('receipt_line_id')->nullable()->constrained()->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreignId('created_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
