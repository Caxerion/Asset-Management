<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
    Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // default Laravel
    $table->string('full_name', 150)->nullable(); // tambahan kalau mau simpan full_name
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password')->nullable();
    $table->string('role')->default('staff');
    $table->rememberToken();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
