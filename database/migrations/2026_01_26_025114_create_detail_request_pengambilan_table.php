<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_request_pengambilan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('request_pengambilan_id')
                  ->constrained('request_pengambilan')
                  ->cascadeOnDelete();

            $table->foreignId('barang_id')
                  ->constrained('barang')
                  ->restrictOnDelete();

            $table->integer('qty');
            $table->text('note')->nullable();

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
        Schema::dropIfExists('detail_request_pengambilan');
    }
};
