<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama');

            $table->foreignId('kategori_id')
                  ->constrained('kategori')
                  ->cascadeOnDelete();

            $table->foreignId('satuan_id')
                  ->constrained('satuan')
                  ->restrictOnDelete();

            $table->foreignId('ukuran_id')
                  ->nullable()
                  ->constrained('ukuran')
                  ->nullOnDelete();

            $table->integer('total_qty')->default(0);

            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
