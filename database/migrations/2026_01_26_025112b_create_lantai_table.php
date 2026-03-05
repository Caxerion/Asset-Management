<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lantai', function (Blueprint $table) {
            $table->id();

            // data utama
            $table->string('nama_lantai');
            $table->string('nama_gedung');

            // PIC lantai (user dengan role = pic)
            $table->foreignId('pic_user_id')
                  ->constrained('users')
                  ->restrictOnDelete();

            // audit
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
        Schema::dropIfExists('lantai');
    }
};
